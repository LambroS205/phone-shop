<?php
namespace App\Core;

class Autoloader {
    public static function register() {
        spl_autoload_register(function (string $class) {
            // Chuyển namespace App\Core\Router thành app/Core/Router.php
            $prefix = 'App\\';
            $base_dir = BASE_PATH . '/app/';

            if (strpos($class, $prefix) !== 0) return;

            $relative_class = substr($class, strlen($prefix));
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
