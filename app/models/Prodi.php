<?php
class Prodi extends Model
{
    protected string $table = 'prodi';

    public function create(array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_prodi', $data['kode_prodi'], 'Kode prodi');
        $stmt = $this->db->prepare('INSERT INTO prodi (kode_prodi, nama_prodi, fakultas, status) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$data['kode_prodi'], $data['nama_prodi'], $data['fakultas'], $data['status']]);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_prodi', $data['kode_prodi'], 'Kode prodi', $id);
        $stmt = $this->db->prepare('UPDATE prodi SET kode_prodi=?, nama_prodi=?, fakultas=?, status=? WHERE id=?');
        return $stmt->execute([$data['kode_prodi'], $data['nama_prodi'], $data['fakultas'], $data['status'], $id]);
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'kode_prodi' => 'Kode prodi',
            'nama_prodi' => 'Nama prodi',
        ]);

        return [
            'kode_prodi' => $this->text($data, 'kode_prodi'),
            'nama_prodi' => $this->text($data, 'nama_prodi'),
            'fakultas' => $this->text($data, 'fakultas'),
            'status' => $this->status($data),
        ];
    }
}
