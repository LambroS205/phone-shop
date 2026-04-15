<?php
declare(strict_types=1);
namespace App\Middleware;
use App\Core\Session;

class RoleMiddleware {
    private string $requiredRole;
    public function __construct(string $role) { $this->requiredRole = $role; }

    public function handle(): bool {
        if (Session::get('user_role') !== $this->requiredRole) {
            http_response_code(403);
            die("403 Forbidden: Insufficient permissions.");
        }
        return true;
    }
}
