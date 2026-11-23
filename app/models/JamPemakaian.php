<?php
class JamPemakaian extends Model
{
    protected string $table = 'jam_pemakaian';

    public function slotOptions(?array $current = null): array
    {
        $items = $this->all('jam_mulai ASC');
        $usedCodes = [];
        $maxNumber = 0;
        $currentCode = $current['kode_jam'] ?? null;

        foreach ($items as $item) {
            $number = jam_number($item);
            $maxNumber = max($maxNumber, $number);

            if (($item['kode_jam'] ?? null) !== $currentCode) {
                $usedCodes[] = $item['kode_jam'];
            }
        }

        if ($current) {
            $maxNumber = max($maxNumber, jam_number($current));
        }

        $slots = standard_time_slots(max(12, $maxNumber + 1));

        return array_values(array_filter($slots, function (array $slot) use ($usedCodes): bool {
            return !in_array($slot['code'], $usedCodes, true);
        }));
    }

    public function create(array $data): bool
    {
        $data = $this->validateData($this->normalizeSlot($data));
        $this->ensureUnique('kode_jam', $data['kode_jam'], 'Kode jam');
        $stmt = $this->db->prepare('INSERT INTO jam_pemakaian (kode_jam, jam_mulai, jam_selesai, keterangan) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$data['kode_jam'], $data['jam_mulai'], $data['jam_selesai'], $data['keterangan']]);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->validateData($this->normalizeSlot($data));
        $this->ensureUnique('kode_jam', $data['kode_jam'], 'Kode jam', $id);
        $stmt = $this->db->prepare('UPDATE jam_pemakaian SET kode_jam=?, jam_mulai=?, jam_selesai=?, keterangan=? WHERE id=?');
        return $stmt->execute([$data['kode_jam'], $data['jam_mulai'], $data['jam_selesai'], $data['keterangan'], $id]);
    }

    private function normalizeSlot(array $data): array
    {
        if (empty($data['slot_code'])) {
            return $data;
        }

        $slotNumber = jam_number(['kode_jam' => $data['slot_code']], 12);

        foreach (standard_time_slots(max(12, $slotNumber)) as $slot) {
            if ($slot['code'] !== $data['slot_code']) {
                continue;
            }

            $data['kode_jam'] = $slot['code'];
            $data['jam_mulai'] = $slot['start_db'];
            $data['jam_selesai'] = $slot['end_db'];
            $data['keterangan'] = trim((string) ($data['keterangan'] ?? '')) ?: $slot['description'];
            break;
        }

        return $data;
    }

    private function validateData(array $data): array
    {
        $this->requireFields($data, [
            'kode_jam' => 'Kode jam',
            'jam_mulai' => 'Jam mulai',
            'jam_selesai' => 'Jam selesai',
        ]);

        $start = $this->normalizeTime($data['jam_mulai'], 'Jam mulai');
        $end = $this->normalizeTime($data['jam_selesai'], 'Jam selesai');

        if ($end <= $start) {
            throw new RuntimeException('Jam selesai harus lebih besar dari jam mulai.');
        }

        return [
            'kode_jam' => $this->text($data, 'kode_jam'),
            'jam_mulai' => $start,
            'jam_selesai' => $end,
            'keterangan' => $this->text($data, 'keterangan'),
        ];
    }

    private function normalizeTime(?string $time, string $label): string
    {
        $time = trim((string) $time);

        if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $time)) {
            throw new RuntimeException($label . ' harus memakai format HH:MM.');
        }

        $parts = array_map('intval', explode(':', $time));
        $hour = $parts[0] ?? -1;
        $minute = $parts[1] ?? -1;
        $second = $parts[2] ?? 0;

        if ($hour > 23 || $minute > 59 || $second > 59) {
            throw new RuntimeException($label . ' tidak valid.');
        }

        return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
    }
}
