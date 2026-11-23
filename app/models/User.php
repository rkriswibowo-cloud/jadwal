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

    public function findAccount(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT u.*, r.name AS role, p.nama_prodi FROM users u
            JOIN roles r ON r.id = u.role_id LEFT JOIN prodi p ON p.id = u.prodi_id WHERE u.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function listWithRole(): array
    {
        return $this->db->query('SELECT u.id, u.name, u.email, u.status, r.name AS role, p.nama_prodi
            FROM users u JOIN roles r ON r.id = u.role_id LEFT JOIN prodi p ON p.id = u.prodi_id
            ORDER BY u.name, u.id')->fetchAll();
    }

    public function roles(): array
    {
        return $this->db->query("SELECT id, name FROM roles WHERE name IN ('admin', 'operator_prodi', 'viewer')
            ORDER BY id")->fetchAll();
    }

    public static function sessionData(array $account): array
    {
        return [
            'id' => (int)$account['id'],
            'name' => $account['name'],
            'email' => $account['email'],
            'role' => $account['role'],
            'prodi_id' => $account['prodi_id'],
            'password_version' => hash('sha256', $account['password']),
        ];
    }

    public function saveManagedAccount(int $actor, ?int $id, array $data): void
    {
        if ($id === $actor) {
            throw new RuntimeException('Gunakan menu Profil untuk mengubah akun sendiri.');
        }
        [$name, $email] = $this->profileFields($data, $id);
        $roleId = $this->intValue($data, 'role_id', 'Peran', 1);
        $roles = array_column($this->roles(), 'name', 'id');
        if (!isset($roles[$roleId])) {
            throw new RuntimeException('Peran tidak valid.');
        }
        $status = $this->status($data);
        $prodiId = $roles[$roleId] === 'operator_prodi'
            ? $this->intValue($data, 'prodi_id', 'Program studi', 1) : null;
        if ($prodiId !== null) {
            $this->requireExisting('prodi', $prodiId, 'Program studi');
        }
        $hash = $this->newPassword($data, $id === null);

        $this->manageAsAdmin($actor, function () use ($id, $name, $email, $roleId, $prodiId, $status, $hash): void {
            if ($id === null) {
                $stmt = $this->db->prepare('INSERT INTO users (name, email, role_id, prodi_id, status, password)
                    VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$name, $email, $roleId, $prodiId, $status, $hash]);
                return;
            }
            $this->requireExisting('users', $id, 'Pengguna');
            $stmt = $this->db->prepare('UPDATE users SET name = ?, email = ?, role_id = ?, prodi_id = ?,
                status = ?, password = COALESCE(?, password) WHERE id = ?');
            $stmt->execute([$name, $email, $roleId, $prodiId, $status, $hash, $id]);
        });
    }

    public function deleteManagedAccount(int $actor, int $id): void
    {
        if ($id === $actor) {
            throw new RuntimeException('Akun yang sedang digunakan tidak dapat dihapus.');
        }
        $this->manageAsAdmin($actor, function () use ($id): void {
            $this->requireExisting('users', $id, 'Pengguna');
            $this->delete($id);
        });
    }

    private function manageAsAdmin(int $actor, callable $operation): void
    {
        $this->db->beginTransaction();
        try {
            // Serialize admin changes so simultaneous requests cannot remove both administrators.
            $member = $this->db->prepare("SELECT id FROM roles WHERE name = 'admin' FOR UPDATE");
            $member->execute();
            $adminRoleId = $member->fetchColumn();
            $stmt = $this->db->prepare("SELECT 1 FROM users WHERE id = ? AND role_id = ? AND status = 'aktif' FOR UPDATE");
            $stmt->execute([$actor, $adminRoleId]);
            if (!$stmt->fetchColumn()) {
                throw new RuntimeException('Hak akses admin sudah berubah.');
            }
            $operation();
            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function saveOwnProfile(int $id, array $data): void
    {
        [$name, $email] = $this->profileFields($data, $id);
        $hash = $this->newPassword($data, false);
        $current = $data['current_password'] ?? '';
        $account = $this->findAccount($id);
        if (!$account || !is_string($current) || !password_verify($current, $account['password'])) {
            throw new RuntimeException('Kata sandi saat ini tidak sesuai.');
        }

        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password = COALESCE(?, password)
            WHERE id = ? AND status = 'aktif' AND BINARY password = ?");
        $stmt->execute([$name, $email, $hash, $id, $account['password']]);
        $updated = $this->findAccount($id);
        if (!$updated || $updated['status'] !== 'aktif' || $updated['password'] !== ($hash ?? $account['password'])
            || $updated['email'] !== $email || $updated['name'] !== $name) {
            throw new RuntimeException('Akun berubah. Muat ulang halaman dan coba lagi.');
        }

        $_SESSION['user'] = self::sessionData($updated);
        if ($hash !== null) {
            session_regenerate_id(true);
            unset($_SESSION['account_csrf']);
        }
    }

    private function profileFields(array $data, ?int $id): array
    {
        $name = is_string($data['name'] ?? null) ? trim($data['name']) : '';
        $email = is_string($data['email'] ?? null) ? trim($data['email']) : '';
        if ($name === '' || mb_strlen($name) > 120) {
            throw new RuntimeException('Isi nama pengguna, maksimal 120 karakter.');
        }
        if (strlen($email) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Masukkan alamat email yang valid.');
        }
        $this->ensureUnique('email', $email, 'Email', $id);
        return [$name, $email];
    }

    private function newPassword(array $data, bool $required): ?string
    {
        $password = $data['password'] ?? '';
        $confirmation = $data['confirm_password'] ?? '';
        if (!$required && $password === '' && $confirmation === '') {
            return null;
        }
        if (!is_string($password) || mb_strlen($password) < 12 || strlen($password) > 72 || str_contains($password, "\0")) {
            throw new RuntimeException('Kata sandi harus minimal 12 karakter dan maksimal 72 byte.');
        }
        if ($password !== $confirmation) {
            throw new RuntimeException('Konfirmasi kata sandi tidak cocok.');
        }
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
