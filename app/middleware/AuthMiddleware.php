<?php
class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (!user()) {
            redirect('login');
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();
        if (!has_role($roles)) {
            http_response_code(403);
            echo '403 - Akses ditolak';
            exit;
        }
    }
}
