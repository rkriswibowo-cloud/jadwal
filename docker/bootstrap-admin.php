<?php

require __DIR__ . '/../app/core/Database.php';

try {
    $db = Database::connection();
    $db->beginTransaction();
    $roleId = $db->query("SELECT id FROM roles WHERE name = 'admin' FOR UPDATE")->fetchColumn();
    if (!$roleId) {
        throw new RuntimeException('Peran admin belum tersedia.');
    }

    $stmt = $db->prepare("SELECT id FROM users WHERE role_id = ? AND status = 'aktif' LIMIT 1");
    $stmt->execute([$roleId]);
    if ($stmt->fetchColumn()) {
        $db->commit();
        exit('Akun admin aktif sudah tersedia.' . PHP_EOL);
    }

    $email = trim(getenv('JADWAL_ADMIN_EMAIL') ?: '');
    $password = getenv('JADWAL_ADMIN_PASSWORD');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 120) {
        throw new RuntimeException('Isi JADWAL_ADMIN_EMAIL dengan alamat email yang valid.');
    }
    if ($password === false || mb_strlen($password) < 12 || strlen($password) > 72 || $password === 'admin123') {
        throw new RuntimeException('Isi JADWAL_ADMIN_PASSWORD dengan kata sandi unik sepanjang 12–72 byte.');
    }
    $stmt = $db->prepare("INSERT INTO users (role_id, name, email, password, status)
        VALUES (?, 'Administrator', ?, ?, 'aktif')");
    $stmt->execute([$roleId, $email, password_hash($password, PASSWORD_DEFAULT)]);
    $db->commit();
    echo 'Akun admin awal siap digunakan.' . PHP_EOL;
} catch (Throwable $exception) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    $message = $exception instanceof PDOException
        ? 'Database belum siap atau email admin sudah digunakan.'
        : $exception->getMessage();
    fwrite(STDERR, 'Setup Jadwal: ' . $message . PHP_EOL);
    exit(1);
}
