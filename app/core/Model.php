<?php
abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    protected function text(array $data, string $key): string
    {
        return trim((string) ($data[$key] ?? ''));
    }

    protected function requireFields(array $data, array $fields): void
    {
        foreach ($fields as $key => $label) {
            if ($this->text($data, $key) === '') {
                throw new RuntimeException($label . ' wajib diisi.');
            }
        }
    }

    protected function status(array $data): string
    {
        $status = $this->text($data, 'status') ?: 'aktif';

        if (!in_array($status, ['aktif', 'nonaktif'], true)) {
            throw new RuntimeException('Status tidak valid.');
        }

        return $status;
    }

    protected function intValue(array $data, string $key, string $label, int $min = 0, ?int $max = null): int
    {
        $value = $this->text($data, $key);

        if ($value === '' || filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new RuntimeException($label . ' harus berupa angka.');
        }

        $number = (int) $value;

        if ($number < $min) {
            throw new RuntimeException($label . ' minimal ' . $min . '.');
        }

        if ($max !== null && $number > $max) {
            throw new RuntimeException($label . ' maksimal ' . $max . '.');
        }

        return $number;
    }

    protected function optionalIntValue(array $data, string $key, string $label, int $min = 0, ?int $max = null): ?int
    {
        if ($this->text($data, $key) === '') {
            return null;
        }

        return $this->intValue($data, $key, $label, $min, $max);
    }

    protected function requireExisting(string $table, int $id, string $label): void
    {
        if ($id <= 0) {
            throw new RuntimeException($label . ' wajib dipilih.');
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);

        if ((int) $stmt->fetchColumn() === 0) {
            throw new RuntimeException($label . ' tidak ditemukan.');
        }
    }

    protected function ensureUnique(string $column, ?string $value, string $label, ?int $ignoreId = null): void
    {
        $value = trim((string) $value);

        if ($value === '') {
            return;
        }

        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE {$column} = ?";
        $params = [$value];

        if ($ignoreId !== null) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        if ((int) $stmt->fetchColumn() > 0) {
            throw new RuntimeException($label . ' sudah digunakan.');
        }
    }
}
