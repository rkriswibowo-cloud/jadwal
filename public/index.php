<?php
session_start();

require __DIR__ . '/../app/helpers/functions.php';
date_default_timezone_set(config_app('timezone', 'Asia/Jakarta'));

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/middleware/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$router = new Router();
require __DIR__ . '/../routes/web.php';
$router->dispatch();
