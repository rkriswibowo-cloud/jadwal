<?php
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void { $this->add('GET', $path, $handler); }
    public function post(string $path, array $handler): void { $this->add('POST', $path, $handler); }

    private function add(string $method, string $path, array $handler): void
    {
        $this->routes[$method][trim($path, '/')] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $base = trim(parse_url(app_base_url(), PHP_URL_PATH) ?: '', '/');
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if ($base && ($uri === $base || str_starts_with($uri, $base . '/'))) {
            $uri = trim(substr($uri, strlen($base)), '/');
        }
        if ($uri === 'public' || str_starts_with($uri, 'public/')) {
            $uri = trim(substr($uri, strlen('public')), '/');
        }

        $uri = $uri ?: 'home';
        $handler = $this->routes[$method][$uri] ?? null;
        if (!$handler) {
            http_response_code(404);
            echo '404 - Halaman tidak ditemukan';
            return;
        }
        [$class, $action] = $handler;
        (new $class())->$action();
    }
}
