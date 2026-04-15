<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class User extends Model {
    protected string $table = 'users';

    // 🔒 Chỉ cho phép cập nhật các trường này
    protected array $fillable = ['name', 'email', 'password'];

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function register(array $data): int {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        // Không cần set role ở đây, DB sẽ dùng DEFAULT 'customer'
        // Vì 'role' không nằm trong fillable nên không thể bị hack

        $data['name'] = Security::sanitize($data['name']);
        $data['email'] = Security::sanitize($data['email']);

        return $this->create($data);
    }

    public function verifyPassword(string $hash, string $password): bool {
        return password_verify($password, $hash);
    }
}
