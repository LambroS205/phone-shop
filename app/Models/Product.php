<?php
namespace App\Models;
use App\Core\Model;

class Product extends Model {
    protected string $table = 'products';

    // ✅ Thêm phương thức này để Controller gọi
    public function getPaginated(int $limit, int $offset): array {
        // Dùng $this->db ở đây là hợp lệ vì đang nằm trong class kế thừa Model
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
