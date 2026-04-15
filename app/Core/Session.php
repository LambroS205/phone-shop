<?php
declare(strict_types=1);
namespace App\Core;

class Session {
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');
            session_start();
        }
        // 🔒 Timeout 30 phút
        $timeout = 1800;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            session_unset(); session_destroy(); session_start();
        }
        $_SESSION['last_activity'] = time();
    }

    public static function put(string $key, mixed $value): void { $_SESSION[$key] = $value; }
    public static function get(string $key, mixed $default = null): mixed { return $_SESSION[$key] ?? $default; }
    public static function has(string $key): bool { return isset($_SESSION[$key]); }
    public static function remove(string $key): void { unset($_SESSION[$key]); }
    public static function forget(string $key): void { unset($_SESSION[$key]); }

    public static function regenerate(): void {
        if (!session_id()) self::init();
        session_regenerate_id(true);
    }

    public static function destroy(): void {
        session_unset();
        session_destroy();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
    }
}
