<?php
$local_file = __DIR__ . '/database.local.php';
$local = is_readable($local_file) ? require $local_file : [];

return [
    'host' => getenv('DB_HOST') ?: ($local['host'] ?? '127.0.0.1'),
    'database' => getenv('DB_DATABASE') ?: ($local['database'] ?? 'db_penjadwalan_ruang'),
    'username' => getenv('DB_USERNAME') ?: ($local['username'] ?? 'root'),
    'password' => getenv('DB_PASSWORD') ?: ($local['password'] ?? ''),
    'charset' => 'utf8mb4',
];
