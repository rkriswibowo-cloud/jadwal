<?php
class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT users.*, roles.name AS role FROM users JOIN roles ON roles.id = users.role_id WHERE users.email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findByIdentifier(string $identifier): ?array
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        // 1. Cari berdasarkan email atau nama di tabel users
        $stmt = $this->db->prepare('SELECT users.*, roles.name AS role FROM users JOIN roles ON roles.id = users.role_id WHERE users.email = ? OR users.name = ? LIMIT 1');
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();
        if ($user) {
            return $user;
        }

        // 2. Coba cari di tabel dosen berdasarkan NIDN atau email dosen
        try {
            $dosenStmt = $this->db->prepare('SELECT email FROM dosen WHERE nidn = ? OR email = ? LIMIT 1');
            $dosenStmt->execute([$identifier, $identifier]);
            $dosen = $dosenStmt->fetch();
            if ($dosen && !empty($dosen['email'])) {
                $stmtUser = $this->db->prepare('SELECT users.*, roles.name AS role FROM users JOIN roles ON roles.id = users.role_id WHERE users.email = ? LIMIT 1');
                $stmtUser->execute([$dosen['email']]);
                $userByDosen = $stmtUser->fetch();
                if ($userByDosen) {
                    return $userByDosen;
                }
            }
        } catch (\Throwable $e) {}

        return null;
    }

    public function listWithRole(): array
    {
        return $this->db->query('SELECT users.*, roles.name AS role_name FROM users JOIN roles ON roles.id = users.role_id ORDER BY users.id DESC')->fetchAll();
    }
}
