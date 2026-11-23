<?php
class AcademicPeriod extends Model
{
    protected string $table = 'academic_periods';

    public function catalog(): array
    {
        return $this->db->query("SELECT academic_year, semester, MAX(is_active) AS is_active, MAX(from_obe) AS from_obe
            FROM (
                SELECT academic_year COLLATE utf8mb4_general_ci AS academic_year,
                    semester COLLATE utf8mb4_general_ci AS semester, is_active, 1 AS from_obe FROM academic_periods
                UNION ALL
                SELECT DISTINCT CONVERT(tahun_akademik USING utf8mb4) COLLATE utf8mb4_general_ci,
                    CONVERT(semester_akademik USING utf8mb4) COLLATE utf8mb4_general_ci, 0, 0 FROM jadwal
                WHERE tahun_akademik REGEXP '^[0-9]{4}/[0-9]{4}$' AND semester_akademik IN ('Ganjil', 'Genap')
            ) p GROUP BY academic_year, semester ORDER BY is_active DESC, academic_year DESC, semester DESC")->fetchAll();
    }

    public function active(): ?array
    {
        return $this->db->query('SELECT academic_year, semester FROM academic_periods WHERE is_active = 1 LIMIT 1')
            ->fetch() ?: null;
    }

    public function syncFromObe(): int
    {
        $file = is_readable('/run/jadwal/obe.php') ? '/run/jadwal/obe.php' : __DIR__ . '/../config/obe.local.php';
        if (!is_readable($file)) {
            throw new RuntimeException('Koneksi OBE belum dikonfigurasi.');
        }
        $config = require $file;
        try {
            $source = new PDO('mysql:unix_socket=' . $config['socket'] . ';dbname=obe_cpl;charset=utf8mb4',
                $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            $rows = $source->query('SELECT nama, is_active FROM tahun_akademik ORDER BY id')->fetchAll();
        } catch (PDOException $exception) {
            error_log('Jadwal: koneksi periode OBE gagal (' . $exception->getCode() . ').');
            throw new RuntimeException('Periode akademik OBE gagal dibaca.');
        }

        $periods = [];
        $activeCount = 0;
        foreach ($rows as $row) {
            $name = trim($row['nama']);
            if (!preg_match('~^([0-9]{4}/[0-9]{4})\s+(Ganjil|Genap)$~iD', $name, $match)) {
                throw new RuntimeException('Format periode di OBE tidak valid. Daftar terakhir tetap tersedia.');
            }
            $semester = ucfirst(strtolower($match[2]));
            $key = $match[1] . ' ' . $semester;
            if (isset($periods[$key])) {
                throw new RuntimeException('OBE memiliki periode ganda. Daftar terakhir tetap tersedia.');
            }
            $active = (int)$row['is_active'] === 1 ? 1 : 0;
            $periods[$key] = [$match[1], $semester, $active];
            $activeCount += $active;
        }
        if ($activeCount !== 1) {
            throw new RuntimeException('OBE harus memiliki tepat satu periode aktif. Daftar terakhir tetap tersedia.');
        }

        $this->db->beginTransaction();
        try {
            $this->db->exec('DELETE FROM academic_periods');
            $stmt = $this->db->prepare('INSERT INTO academic_periods (academic_year, semester, is_active) VALUES (?, ?, ?)');
            foreach ($periods as $period) {
                $stmt->execute($period);
            }
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }

        return count($periods);
    }
}
