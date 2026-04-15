<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function register(array $data): int {
        $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
        $data['role'] = 'customer';
        // Sanitize trước khi insert
        $data['name'] = Security::sanitize($data['name']);
        $data['email'] = Security::sanitize($data['email']);
        return $this->create($data);
    }

    public function verifyPassword(string $hash, string $password): bool {
        return password_verify($password, $hash);
    }
}
