<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model {
    protected PDO $db;
    protected string $table = '';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy tất cả bản ghi
    public function findAll(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tìm theo ID
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // Insert an toàn
    public function create(array $data): int {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($fields) VALUES ($placeholders)");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    // ✅ MỚI: Cập nhật bản ghi
    public function update(int $id, array $data): bool {
        $fields = [];
        foreach ($data as $key => $value) {
            // Bỏ qua ID nếu có trong data để tránh update khóa chính
            if ($key === 'id') continue;
            $fields[] = "$key = :$key";
        }
        $fieldStr = implode(', ', $fields);

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $fieldStr WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // ✅ MỚI: Xóa bản ghi
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ✅ MỚI: Đếm số lượng bản ghi (dùng cho phân trang)
    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }
} // ⚠️ Dấu đóng class phải nằm ở cuối cùng
