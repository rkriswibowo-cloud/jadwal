<?php
class MataKuliah extends Model
{
    protected string $table = 'mata_kuliah';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    private function ensureTable(): void
    {
        static $ensured = false;

        if ($ensured) {
            return;
        }

        $this->db->exec("CREATE TABLE IF NOT EXISTS mata_kuliah (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode_mk VARCHAR(30) NOT NULL UNIQUE,
            nama_mk VARCHAR(150) NOT NULL UNIQUE,
            sks INT NOT NULL DEFAULT 0,
            semester INT NULL,
            status ENUM('aktif','nonaktif') DEFAULT 'aktif',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $this->seedFromExistingSchedules();

        $ensured = true;
    }

    private function seedFromExistingSchedules(): void
    {
        $rows = $this->db->query("SELECT MIN(id) AS source_id, mata_kuliah
            FROM jadwal
            WHERE mata_kuliah IS NOT NULL AND TRIM(mata_kuliah) <> ''
            GROUP BY mata_kuliah")->fetchAll();

        $existsByName = $this->db->prepare('SELECT COUNT(*) FROM mata_kuliah WHERE nama_mk = ?');
        $insert = $this->db->prepare('INSERT INTO mata_kuliah (kode_mk, nama_mk, status) VALUES (?, ?, "aktif")');

        foreach ($rows as $row) {
            $name = trim((string) $row['mata_kuliah']);
            $existsByName->execute([$name]);

            if ((int) $existsByName->fetchColumn() > 0) {
                continue;
            }

            $insert->execute([$this->uniqueCode((int) $row['source_id']), $name]);
        }
    }

    private function uniqueCode(int $sourceId): string
    {
        $base = 'MK-' . $sourceId;
        $code = $base;
        $counter = 1;
        $exists = $this->db->prepare('SELECT COUNT(*) FROM mata_kuliah WHERE kode_mk = ?');

        while (true) {
            $exists->execute([$code]);

            if ((int) $exists->fetchColumn() === 0) {
                return $code;
            }

            $code = $base . '-' . $counter;
            $counter++;
        }
    }

    public function activeOptions(): array
    {
        return $this->db->query('SELECT * FROM mata_kuliah WHERE status = "aktif" ORDER BY nama_mk ASC')->fetchAll();
    }

    public function create(array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_mk', $data['kode_mk'], 'Kode mata kuliah');
        $this->ensureUnique('nama_mk', $data['nama_mk'], 'Nama mata kuliah');
        $stmt = $this->db->prepare('INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, status) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['kode_mk'],
            $data['nama_mk'],
            (int) ($data['sks'] ?? 0),
            ($data['semester'] ?? '') !== '' ? (int) $data['semester'] : null,
            $data['status'] ?? 'aktif',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_mk', $data['kode_mk'], 'Kode mata kuliah', $id);
        $this->ensureUnique('nama_mk', $data['nama_mk'], 'Nama mata kuliah', $id);
        $stmt = $this->db->prepare('UPDATE mata_kuliah SET kode_mk=?, nama_mk=?, sks=?, semester=?, status=? WHERE id=?');
        return $stmt->execute([
            $data['kode_mk'],
            $data['nama_mk'],
            (int) ($data['sks'] ?? 0),
            ($data['semester'] ?? '') !== '' ? (int) $data['semester'] : null,
            $data['status'] ?? 'aktif',
            $id,
        ]);
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'kode_mk' => 'Kode mata kuliah',
            'nama_mk' => 'Nama mata kuliah',
        ]);

        return [
            'kode_mk' => $this->text($data, 'kode_mk'),
            'nama_mk' => $this->text($data, 'nama_mk'),
            'sks' => $this->intValue($data, 'sks', 'SKS', 0, 24),
            'semester' => $this->optionalIntValue($data, 'semester', 'Semester', 1, 14),
            'status' => $this->status($data),
        ];
    }
}
