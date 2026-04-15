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

    // 2. Thêm sản phẩm (Admin POST)
    public function store() {
        // 1. Kiểm tra Admin
        if (Session::get('user_role') !== 'admin') {
            die("Unauthorized");
        }

        // 2. Xử lý dữ liệu
        $data = [
            'name' => htmlspecialchars($_POST['name']),
            'price' => (float)$_POST['price'],
            'category_id' => (int)$_POST['category_id'],
            'stock' => (int)$_POST['stock'],
            'description' => htmlspecialchars($_POST['description']),
            'image' => $_POST['image'] ?? 'default-phone.jpg' // Tạm nhận URL ảnh
        ];

        $this->model->create($data);
        header('Location: /admin/products?status=success');
        exit;
    }

    // 3. Xóa sản phẩm (Admin POST)
    public function destroy() {
        if (Session::get('user_role') !== 'admin') die("Unauthorized");

        $id = (int)$_POST['id'];
        $this->model->delete($id);
        header('Location: /admin/products');
        exit;
    }
}
