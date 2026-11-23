<?php
class Ruang extends Model
{
    protected string $table = 'ruang';

    public function create(array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_ruang', $data['kode_ruang'], 'Kode ruang');
        $stmt = $this->db->prepare('INSERT INTO ruang (kode_ruang, nama_ruang, kapasitas, lokasi, status) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$data['kode_ruang'], $data['nama_ruang'], $data['kapasitas'], $data['lokasi'], $data['status']]);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($data);
        $this->ensureUnique('kode_ruang', $data['kode_ruang'], 'Kode ruang', $id);
        $stmt = $this->db->prepare('UPDATE ruang SET kode_ruang=?, nama_ruang=?, kapasitas=?, lokasi=?, status=? WHERE id=?');
        return $stmt->execute([$data['kode_ruang'], $data['nama_ruang'], $data['kapasitas'], $data['lokasi'], $data['status'], $id]);
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'kode_ruang' => 'Kode ruang',
            'nama_ruang' => 'Nama ruang',
        ]);

        return [
            'kode_ruang' => $this->text($data, 'kode_ruang'),
            'nama_ruang' => $this->text($data, 'nama_ruang'),
            'kapasitas' => $this->intValue($data, 'kapasitas', 'Kapasitas', 1),
            'lokasi' => $this->text($data, 'lokasi'),
            'status' => $this->status($data),
        ];
    }
}
