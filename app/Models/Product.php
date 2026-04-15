<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;

class Product extends Model {
    protected string $table = 'products';

    // 🔒 Whitelist cho Product
    protected array $fillable = [
        'name', 'price', 'stock', 'category_id', 'description', 'image'
    ];

    public function getPaginated(int $limit, int $offset): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
