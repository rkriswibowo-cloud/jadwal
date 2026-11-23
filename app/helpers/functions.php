<?php
function config_app(string $key, $default = null)
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config/app.php';
    }
    return $config[$key] ?? $default;
}

function app_base_url(): string
{
    static $baseUrl = null;
    if ($baseUrl !== null) {
        return $baseUrl;
    }

    $configured = trim((string) config_app('base_url', ''));
    if ($configured !== '') {
        return $baseUrl = rtrim($configured, '/');
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $basePath = str_replace('\\', '/', dirname($scriptName));

    if ($basePath === '/' || $basePath === '.') {
        return $baseUrl = '';
    }

    $basePath = rtrim($basePath, '/');
    if (str_ends_with($basePath, '/public')) {
        $basePath = substr($basePath, 0, -7);
    }

    return $baseUrl = rtrim($basePath, '/');
}

function url(string $path = ''): string
{
    return rtrim(app_base_url(), '/') . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function active_menu(string $segment): string
{
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    return str_contains($path, $segment) ? 'active' : '';
}

function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function user(): ?array
{
    static $checked = false;
    if (!$checked && isset($_SESSION['user'])) {
        $checked = true;
        $previous = $_SESSION['user'];
        $account = (new User())->findAccount((int)$previous['id']);
        if (!$account || $account['status'] !== 'aktif') {
            unset($_SESSION['user'], $_SESSION['account_csrf']);
            return null;
        }
        $session = User::sessionData($account);
        if (isset($previous['password_version']) && $previous['password_version'] !== $session['password_version']) {
            unset($_SESSION['user'], $_SESSION['account_csrf']);
            return null;
        }
        $_SESSION['user'] = $session;
    }
    return $_SESSION['user'] ?? null;
}

function account_csrf_token(): string
{
    return $_SESSION['account_csrf'] ??= bin2hex(random_bytes(32));
}

function require_account_csrf(): void
{
    $provided = $_POST['csrf_token'] ?? null;
    if (!is_string($provided) || !hash_equals(account_csrf_token(), $provided)) {
        http_response_code(403);
        exit('Token formulir tidak valid. Muat ulang halaman dan coba lagi.');
    }
}

function account_role_label(string $role): string
{
    return match ($role) {
        'admin' => 'Admin',
        'operator_prodi' => 'Operator Prodi',
        'viewer' => 'Viewer',
        default => $role,
    };
}

function has_role(array $roles): bool
{
    $user = user();
    return $user && in_array($user['role'], $roles, true);
}

function academic_days(): array
{
    return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
}

function standard_time_slots(int $count = 12): array
{
    static $slots = [];

    if (isset($slots[$count])) {
        return $slots[$count];
    }

    $items = [];
    $start = new DateTimeImmutable('07:30');

    for ($number = 1; $number <= $count; $number++) {
        $end = $start->modify('+50 minutes');
        $items[] = [
            'number' => $number,
            'code' => 'JAM' . $number,
            'label' => 'Jam ke-' . $number,
            'start' => $start->format('H:i'),
            'end' => $end->format('H:i'),
            'start_db' => $start->format('H:i:s'),
            'end_db' => $end->format('H:i:s'),
            'description' => 'Jam ke-' . $number,
        ];
        $start = $end;
    }

    return $slots[$count] = $items;
}

function time_short(?string $time): string
{
    return substr((string) $time, 0, 5);
}

function jam_number(array $item, int $fallback = 0): int
{
    if (preg_match('/(\d+)/', (string) ($item['kode_jam'] ?? ''), $matches)) {
        return (int) $matches[1];
    }

    return $fallback;
}
