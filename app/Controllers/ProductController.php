<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Product;
use App\Core\Session;

class ProductController {
    private Product $model;

    // ✅ Constructor Injection: Controller nhận Model từ bên ngoài
    public function __construct(Product $model) {
        $this->model = $model;
    }

    public function index() {
        $limit = 8;
        $page = (int)($_GET['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        // Controller chỉ lo điều phối, không lo tạo Model
        $products = $this->model->getPaginated($limit, $offset);
        $total = $this->model->countAll();
        $totalPages = ceil($total / $limit);

        require BASE_PATH . '/app/Views/products/list.php';
    }
}
