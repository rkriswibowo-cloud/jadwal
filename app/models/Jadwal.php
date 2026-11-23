<?php
class Jadwal extends Model
{
    protected string $table = 'jadwal';

    public function __construct()
    {
        parent::__construct();
        $this->ensureClassRelationTable();
    }

    private function ensureClassRelationTable(): void
    {
        static $ensured = false;

        if ($ensured) {
            return;
        }

        $this->db->exec("CREATE TABLE IF NOT EXISTS jadwal_kelas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            jadwal_id INT NOT NULL,
            kelas_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_jadwal_kelas_jadwal FOREIGN KEY (jadwal_id) REFERENCES jadwal(id) ON DELETE CASCADE,
            CONSTRAINT fk_jadwal_kelas_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE RESTRICT,
            UNIQUE KEY uniq_jadwal_kelas (jadwal_id, kelas_id),
            KEY idx_jadwal_kelas_kelas (kelas_id)
        )");

        $this->db->exec('INSERT IGNORE INTO jadwal_kelas (jadwal_id, kelas_id) SELECT id, kelas_id FROM jadwal');
        $this->ensureRoomSlotIndexAllowsCombinedClasses();
        $ensured = true;
    }

    private function ensureRoomSlotIndexAllowsCombinedClasses(): void
    {
        try {
            $stmt = $this->db->query("SHOW INDEX FROM jadwal WHERE Key_name = 'idx_jadwal_ruang_slot'");
            if (!$stmt->fetch()) {
                $this->db->exec('ALTER TABLE jadwal ADD INDEX idx_jadwal_ruang_slot (ruang_id, hari, jam_id, semester_akademik, tahun_akademik)');
            }

            $stmt = $this->db->query("SHOW INDEX FROM jadwal WHERE Key_name = 'uniq_ruang_slot'");
            if ($stmt->fetch()) {
                $this->db->exec('ALTER TABLE jadwal DROP INDEX uniq_ruang_slot');
            }
        } catch (PDOException $e) {
            // Database migration file keeps the same change available for restricted hosting.
        }
    }

    private function detailSelect(): string
    {
        return 'SELECT jadwal.*, ruang.id AS room_id, ruang.nama_ruang, ruang.kode_ruang,
                    kelas_utama.nama_kelas AS nama_kelas_utama,
                    kelas_utama.kode_kelas AS kode_kelas_utama,
                    prodi_utama.nama_prodi AS nama_prodi_utama,
                    COALESCE(kelas_group.kelas_ids, CAST(jadwal.kelas_id AS CHAR)) AS kelas_ids,
                    COALESCE(kelas_group.kode_kelas, kelas_utama.kode_kelas) AS kode_kelas,
                    COALESCE(kelas_group.nama_kelas, kelas_utama.nama_kelas) AS nama_kelas,
                    COALESCE(kelas_group.nama_prodi, prodi_utama.nama_prodi) AS nama_prodi,
                    COALESCE(kelas_group.total_kelas, 1) AS total_kelas,
                    jam_pemakaian.kode_jam, jam_pemakaian.jam_mulai, jam_pemakaian.jam_selesai
                FROM jadwal
                JOIN ruang ON ruang.id = jadwal.ruang_id
                JOIN kelas kelas_utama ON kelas_utama.id = jadwal.kelas_id
                JOIN prodi prodi_utama ON prodi_utama.id = kelas_utama.prodi_id
                JOIN jam_pemakaian ON jam_pemakaian.id = jadwal.jam_id
                LEFT JOIN (
                    SELECT jadwal_kelas.jadwal_id,
                        GROUP_CONCAT(kelas.id ORDER BY prodi.nama_prodi, kelas.kode_kelas SEPARATOR ",") AS kelas_ids,
                        GROUP_CONCAT(kelas.kode_kelas ORDER BY prodi.nama_prodi, kelas.kode_kelas SEPARATOR ", ") AS kode_kelas,
                        GROUP_CONCAT(kelas.nama_kelas ORDER BY prodi.nama_prodi, kelas.kode_kelas SEPARATOR ", ") AS nama_kelas,
                        GROUP_CONCAT(DISTINCT prodi.nama_prodi ORDER BY prodi.nama_prodi SEPARATOR ", ") AS nama_prodi,
                        COUNT(*) AS total_kelas
                    FROM jadwal_kelas
                    JOIN kelas ON kelas.id = jadwal_kelas.kelas_id
                    JOIN prodi ON prodi.id = kelas.prodi_id
                    GROUP BY jadwal_kelas.jadwal_id
                ) kelas_group ON kelas_group.jadwal_id = jadwal.id';
    }

    private function detailOrder(): string
    {
        return ' ORDER BY FIELD(jadwal.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"), jam_pemakaian.jam_mulai';
    }

    public function allDetail(array $filters = []): array
    {
        [$where, $params] = $this->detailFilters($filters);
        $stmt = $this->db->prepare($this->detailSelect() . $where . $this->detailOrder());
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    private function detailFilters(array $filters): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['hari'])) {
            $where[] = 'jadwal.hari = ?';
            $params[] = $filters['hari'];
        }

        if (!empty($filters['ruang_id'])) {
            $where[] = 'jadwal.ruang_id = ?';
            $params[] = (int) $filters['ruang_id'];
        }

        if (!empty($filters['prodi_id'])) {
            $where[] = 'EXISTS (
                SELECT 1 FROM jadwal_kelas jk_filter
                JOIN kelas kelas_filter ON kelas_filter.id = jk_filter.kelas_id
                WHERE jk_filter.jadwal_id = jadwal.id AND kelas_filter.prodi_id = ?
            )';
            $params[] = (int) $filters['prodi_id'];
        }

        if (!empty($filters['tahun_akademik'])) {
            $where[] = 'jadwal.tahun_akademik = ?';
            $params[] = $filters['tahun_akademik'];
        }

        if (!empty($filters['status'])) {
            $where[] = 'jadwal.status = ?';
            $params[] = $filters['status'];
        }

        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public function findWithClasses(int $id): ?array
    {
        $item = $this->find($id);

        if (!$item) {
            return null;
        }

        $item['kelas_ids'] = $this->classIdsForSchedule($id);

        if (!$item['kelas_ids']) {
            $item['kelas_ids'] = [(int) $item['kelas_id']];
        }

        return $item;
    }

    public function classIdsForSchedule(int $jadwalId): array
    {
        $stmt = $this->db->prepare('SELECT kelas_id FROM jadwal_kelas WHERE jadwal_id = ? ORDER BY id');
        $stmt->execute([$jadwalId]);

        return array_map('intval', array_column($stmt->fetchAll(), 'kelas_id'));
    }

    private function normalizeClassIds(array $data): array
    {
        $rawClassIds = $data['kelas_ids'] ?? ($data['kelas_id'] ?? []);

        if (!is_array($rawClassIds)) {
            $rawClassIds = [$rawClassIds];
        }

        $classIds = [];
        foreach ($rawClassIds as $classId) {
            $classId = (int) $classId;

            if ($classId > 0 && !in_array($classId, $classIds, true)) {
                $classIds[] = $classId;
            }
        }

        if (!$classIds) {
            throw new RuntimeException('Pilih minimal satu kelas untuk jadwal.');
        }

        foreach ($classIds as $classId) {
            $this->requireExisting('kelas', $classId, 'Kelas');
        }

        return $classIds;
    }

    public function checkConflict(array $data, ?int $ignoreId = null): ?array
    {
        $classIds = $this->normalizeClassIds($data);
        $slotParams = [
            $data['hari'],
            $data['tahun_akademik'],
            $data['semester_akademik'],
            $data['jam_id'],
        ];

        $placeholders = implode(',', array_fill(0, count($classIds), '?'));
        $classSql = 'SELECT * FROM jadwal
                     WHERE hari = ? AND tahun_akademik = ? AND semester_akademik = ? AND jam_id = ?
                     AND (
                        kelas_id IN (' . $placeholders . ')
                        OR EXISTS (
                            SELECT 1 FROM jadwal_kelas
                            WHERE jadwal_kelas.jadwal_id = jadwal.id
                            AND jadwal_kelas.kelas_id IN (' . $placeholders . ')
                        )
                     )';
        $classParams = array_merge($slotParams, $classIds, $classIds);

        if ($ignoreId) {
            $classSql .= ' AND id != ?';
            $classParams[] = $ignoreId;
        }

        $stmt = $this->db->prepare($classSql . ' LIMIT 1');
        $stmt->execute($classParams);
        $classConflict = $stmt->fetch();

        if ($classConflict) {
            $classConflict['type'] = 'kelas';
            return $classConflict;
        }

        return null;
    }

    public function create(array $data): bool
    {
        $data = $this->validateData($data);
        $classIds = $this->normalizeClassIds($data);
        $data['kelas_id'] = $classIds[0];

        if ($conflict = $this->checkConflict($data)) {
            throw new RuntimeException($this->conflictMessage($conflict));
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('INSERT INTO jadwal (ruang_id, kelas_id, jam_id, hari, mata_kuliah, dosen, semester_akademik, tahun_akademik, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $data['ruang_id'],
                $data['kelas_id'],
                $data['jam_id'],
                $data['hari'],
                $data['mata_kuliah'],
                $data['dosen'],
                $data['semester_akademik'],
                $data['tahun_akademik'],
                $data['status'] ?? 'aktif',
            ]);

            $this->syncClasses((int) $this->db->lastInsertId(), $classIds);
            $this->db->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($data);
        $classIds = $this->normalizeClassIds($data);
        $data['kelas_id'] = $classIds[0];

        if ($conflict = $this->checkConflict($data, $id)) {
            throw new RuntimeException($this->conflictMessage($conflict));
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('UPDATE jadwal SET ruang_id=?, kelas_id=?, jam_id=?, hari=?, mata_kuliah=?, dosen=?, semester_akademik=?, tahun_akademik=?, status=? WHERE id=?');
            $stmt->execute([
                $data['ruang_id'],
                $data['kelas_id'],
                $data['jam_id'],
                $data['hari'],
                $data['mata_kuliah'],
                $data['dosen'],
                $data['semester_akademik'],
                $data['tahun_akademik'],
                $data['status'] ?? 'aktif',
                $id,
            ]);

            $this->syncClasses($id, $classIds);
            $this->db->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    private function syncClasses(int $jadwalId, array $classIds): void
    {
        $stmt = $this->db->prepare('DELETE FROM jadwal_kelas WHERE jadwal_id = ?');
        $stmt->execute([$jadwalId]);

        $stmt = $this->db->prepare('INSERT INTO jadwal_kelas (jadwal_id, kelas_id) VALUES (?, ?)');
        foreach ($classIds as $classId) {
            $stmt->execute([$jadwalId, $classId]);
        }
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'hari' => 'Hari',
            'mata_kuliah' => 'Mata kuliah',
            'semester_akademik' => 'Semester akademik',
            'tahun_akademik' => 'Tahun akademik',
        ]);

        $roomId = $this->intValue($data, 'ruang_id', 'Ruang', 1);
        $jamId = $this->intValue($data, 'jam_id', 'Jam', 1);
        $this->requireExisting('ruang', $roomId, 'Ruang');
        $this->requireExisting('jam_pemakaian', $jamId, 'Jam');

        $day = $this->text($data, 'hari');
        if (!in_array($day, academic_days(), true)) {
            throw new RuntimeException('Hari tidak valid.');
        }

        $semesterAkademik = $this->text($data, 'semester_akademik');
        if (!in_array($semesterAkademik, ['Ganjil', 'Genap'], true)) {
            throw new RuntimeException('Semester akademik tidak valid.');
        }

        if (!preg_match('/^\d{4}\/\d{4}$/', $this->text($data, 'tahun_akademik'))) {
            throw new RuntimeException('Tahun akademik harus memakai format 2025/2026.');
        }

        return [
            'ruang_id' => $roomId,
            'kelas_ids' => $data['kelas_ids'] ?? ($data['kelas_id'] ?? []),
            'jam_id' => $jamId,
            'hari' => $day,
            'mata_kuliah' => $this->text($data, 'mata_kuliah'),
            'dosen' => $this->text($data, 'dosen'),
            'semester_akademik' => $semesterAkademik,
            'tahun_akademik' => $this->text($data, 'tahun_akademik'),
            'status' => $this->status($data),
        ];
    }

    private function conflictMessage(array $conflict): string
    {
        return 'Jadwal bentrok: salah satu kelas sudah memiliki jadwal pada hari dan jam yang sama.';
    }

    public function scheduleForClass(int $kelasId, string $semesterAkademik = '', string $tahunAkademik = ''): array
    {
        $where = ' WHERE jadwal.status = "aktif"
                   AND (
                      jadwal.kelas_id = ?
                      OR EXISTS (
                          SELECT 1 FROM jadwal_kelas
                          WHERE jadwal_kelas.jadwal_id = jadwal.id
                          AND jadwal_kelas.kelas_id = ?
                      )
                   )';
        $params = [$kelasId, $kelasId];

        if ($semesterAkademik !== '') {
            $where .= ' AND jadwal.semester_akademik = ?';
            $params[] = $semesterAkademik;
        }

        if ($tahunAkademik !== '') {
            $where .= ' AND jadwal.tahun_akademik = ?';
            $params[] = $tahunAkademik;
        }

        $stmt = $this->db->prepare($this->detailSelect() . $where . $this->detailOrder());
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function scheduleForDosen(string $dosenName, string $semesterAkademik = '', string $tahunAkademik = ''): array
    {
        $where = ' WHERE jadwal.status = "aktif" AND LOWER(TRIM(jadwal.dosen)) = LOWER(?)';
        $params = [trim($dosenName)];

        if ($semesterAkademik !== '') {
            $where .= ' AND jadwal.semester_akademik = ?';
            $params[] = $semesterAkademik;
        }

        if ($tahunAkademik !== '') {
            $where .= ' AND jadwal.tahun_akademik = ?';
            $params[] = $tahunAkademik;
        }

        $stmt = $this->db->prepare($this->detailSelect() . $where . $this->detailOrder());
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function academicYears(): array
    {
        $stmt = $this->db->query('SELECT CONVERT(tahun_akademik USING utf8mb4) COLLATE utf8mb4_general_ci AS tahun_akademik
            FROM jadwal UNION SELECT academic_year COLLATE utf8mb4_general_ci FROM academic_periods ORDER BY tahun_akademik DESC');
        return array_column($stmt->fetchAll(), 'tahun_akademik');
    }

    public function viewerCards(): array
    {
        $rooms = $this->db->query('SELECT * FROM ruang WHERE status="aktif" ORDER BY nama_ruang')->fetchAll();
        $items = $this->db->query($this->detailSelect() . ' WHERE jadwal.status="aktif"' . $this->detailOrder())->fetchAll();

        foreach ($rooms as &$room) {
            $room['jadwal'] = array_values(array_filter($items, fn($row) => (int) $row['room_id'] === (int) $room['id']));
        }

        return $rooms;
    }
}
