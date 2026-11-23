<?php
class Kelas extends Model
{
    protected string $table = 'kelas';

    public function allWithProdi(): array
    {
        return $this->db->query('SELECT kelas.*, prodi.nama_prodi FROM kelas JOIN prodi ON prodi.id = kelas.prodi_id ORDER BY prodi.nama_prodi ASC, kelas.kode_kelas ASC')->fetchAll();
    }

    public function findWithProdi(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT kelas.*, prodi.nama_prodi FROM kelas JOIN prodi ON prodi.id = kelas.prodi_id WHERE kelas.id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_kelas', $data['kode_kelas'], 'Kode kelas');
        $stmt = $this->db->prepare('INSERT INTO kelas (prodi_id, kode_kelas, nama_kelas, angkatan, semester, jumlah_mahasiswa, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$data['prodi_id'], $data['kode_kelas'], $data['nama_kelas'], $data['angkatan'], $data['semester'], $data['jumlah_mahasiswa'], $data['status']]);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_kelas', $data['kode_kelas'], 'Kode kelas', $id);
        $stmt = $this->db->prepare('UPDATE kelas SET prodi_id=?, kode_kelas=?, nama_kelas=?, angkatan=?, semester=?, jumlah_mahasiswa=?, status=? WHERE id=?');
        return $stmt->execute([$data['prodi_id'], $data['kode_kelas'], $data['nama_kelas'], $data['angkatan'], $data['semester'], $data['jumlah_mahasiswa'], $data['status'], $id]);
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'kode_kelas' => 'Kode kelas',
            'nama_kelas' => 'Nama kelas',
        ]);

        $prodiId = $this->intValue($data, 'prodi_id', 'Prodi', 1);
        $this->requireExisting('prodi', $prodiId, 'Prodi');

        return [
            'prodi_id' => $prodiId,
            'kode_kelas' => $this->text($data, 'kode_kelas'),
            'nama_kelas' => $this->text($data, 'nama_kelas'),
            'angkatan' => $this->intValue($data, 'angkatan', 'Angkatan', 2000, 2100),
            'semester' => $this->intValue($data, 'semester', 'Semester', 1, 14),
            'jumlah_mahasiswa' => $this->intValue($data, 'jumlah_mahasiswa', 'Jumlah mahasiswa', 0),
            'status' => $this->status($data),
        ];
    }
}
