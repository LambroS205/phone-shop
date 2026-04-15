<?php
declare(strict_types=1);
namespace App\Core;

class Security {
    public static function csrfToken(): string {
        if (!Session::has('csrf_token')) {
            Session::put('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function verifyCsrf(string $token): bool {
        return hash_equals(Session::get('csrf_token'), $token);
    }

    // XSS Filter cơ bản
    public static function sanitize(string $input): string {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
