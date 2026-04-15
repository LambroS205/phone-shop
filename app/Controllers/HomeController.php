<?php
declare(strict_types=1);

namespace App\Controllers;

// Import Model từ đúng thư mục Models
use App\Models\Product;

class HomeController {
    public function index() {
        // Khởi tạo Model
        $productModel = new Product();
        $products = $productModel->findAll();

        // Hiển thị kết quả (Tạm thời echo để test, sau này sẽ render View)
        echo "<pre style='font-family: monospace; font-size: 14px;'>";
        echo "✅ PDO Connected & PSR-4 Active!\n";
        echo "📦 Tổng số sản phẩm trong DB: " . count($products) . "\n";
        echo "</pre>";
    }
}
