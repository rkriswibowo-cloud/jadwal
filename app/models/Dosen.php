<?php
class Dosen extends Model
{
    protected string $table = 'dosen';

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

        $this->db->exec("CREATE TABLE IF NOT EXISTS dosen (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nidn VARCHAR(30) NULL UNIQUE,
            nama_dosen VARCHAR(150) NOT NULL UNIQUE,
            email VARCHAR(120) NULL,
            status ENUM('aktif','nonaktif') DEFAULT 'aktif',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $this->seedFromExistingSchedules();

        $ensured = true;
    }

    private function seedFromExistingSchedules(): void
    {
        $rows = $this->db->query("SELECT MIN(id) AS source_id, dosen
            FROM jadwal
            WHERE dosen IS NOT NULL AND TRIM(dosen) <> ''
            GROUP BY dosen")->fetchAll();

        $existsByName = $this->db->prepare('SELECT COUNT(*) FROM dosen WHERE nama_dosen = ?');
        $insert = $this->db->prepare('INSERT INTO dosen (nidn, nama_dosen, status) VALUES (?, ?, "aktif")');

        foreach ($rows as $row) {
            $name = trim((string) $row['dosen']);
            $existsByName->execute([$name]);

            if ((int) $existsByName->fetchColumn() > 0) {
                continue;
            }

            $insert->execute([$this->uniqueNidn((int) $row['source_id']), $name]);
        }
    }

    private function uniqueNidn(int $sourceId): string
    {
        $base = 'DSN-' . $sourceId;
        $nidn = $base;
        $counter = 1;
        $exists = $this->db->prepare('SELECT COUNT(*) FROM dosen WHERE nidn = ?');

        while (true) {
            $exists->execute([$nidn]);

            if ((int) $exists->fetchColumn() === 0) {
                return $nidn;
            }

            $nidn = $base . '-' . $counter;
            $counter++;
        }
    }

    public function activeOptions(): array
    {
        return $this->db->query('SELECT * FROM dosen WHERE status = "aktif" ORDER BY nama_dosen ASC')->fetchAll();
    }

    public function create(array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('nidn', $data['nidn'], 'NIDN');
        $this->ensureUnique('nama_dosen', $data['nama_dosen'], 'Nama dosen');
        $stmt = $this->db->prepare('INSERT INTO dosen (nidn, nama_dosen, email, status) VALUES (?, ?, ?, ?)');
        return $stmt->execute([
            $data['nidn'],
            $data['nama_dosen'],
            $data['email'],
            $data['status'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('nidn', $data['nidn'], 'NIDN', $id);
        $this->ensureUnique('nama_dosen', $data['nama_dosen'], 'Nama dosen', $id);
        $stmt = $this->db->prepare('UPDATE dosen SET nidn=?, nama_dosen=?, email=?, status=? WHERE id=?');
        return $stmt->execute([
            $data['nidn'],
            $data['nama_dosen'],
            $data['email'],
            $data['status'],
            $id,
        ]);
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'nama_dosen' => 'Nama dosen',
        ]);

        $email = $this->nullableValue($data['email'] ?? '');

        if ($email !== null && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Email dosen tidak valid.');
        }

        return [
            'nidn' => $this->nullableValue($data['nidn'] ?? ''),
            'nama_dosen' => $this->text($data, 'nama_dosen'),
            'email' => $email,
            'status' => $this->status($data),
        ];
    }

    private function nullableValue(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}
