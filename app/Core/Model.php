<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

abstract class Model {
    protected PDO $db;
    protected string $table = '';

    // 🔒 WHITELIST: Chỉ các trường trong mảng này mới được phép Insert/Update
    protected array $fillable = [];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // ✅ FIX: Lọc dữ liệu trước khi Insert
    public function create(array $data): int {
        // 1. Chỉ giữ lại các key có trong $fillable
        $safeData = array_intersect_key($data, array_flip($this->fillable));

        if (empty($safeData)) {
            throw new \Exception("Dữ liệu không hợp lệ hoặc trống.");
        }

        $fields = implode(', ', array_keys($safeData));
        $placeholders = ':' . implode(', :', array_keys($safeData));

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($fields) VALUES ($placeholders)");
        $stmt->execute($safeData);
        return (int) $this->db->lastInsertId();
    }

    // ✅ FIX: Lọc dữ liệu trước khi Update
    public function update(int $id, array $data): bool {
        // 1. Lọc dữ liệu
        $safeData = array_intersect_key($data, array_flip($this->fillable));

        if (empty($safeData)) return true; // Không có gì để cập nhật

        $fields = [];
        foreach ($safeData as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fieldStr = implode(', ', $fields);

        // 2. Thêm ID vào mảng binding cho WHERE clause
        $safeData['id'] = $id;

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $fieldStr WHERE id = :id");
        return $stmt->execute($safeData);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }
}
