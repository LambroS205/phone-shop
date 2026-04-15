<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Cart;
use App\Core\Security;

class CartController {
    private Cart $cart;

    public function __construct(Cart $cart) {
        $this->cart = $cart;
    }

    // Helper kiểm tra AJAX
    private function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function index() {
        require BASE_PATH . '/app/Views/cart/index.php';
    }

    public function add() {
        // 1. Verify CSRF (Bắt buộc)
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) {
            $this->response(false, "Lỗi bảo mật CSRF");
            return;
        }

        // 2. Lấy dữ liệu
        $id = (int)$_POST['id'];
        $name = htmlspecialchars($_POST['name']);
        $price = (float)$_POST['price'];
        $image = htmlspecialchars($_POST['image']);

        // 3. Xử lý Logic
        $this->cart->add($id, $name, $price, $image, 1);

        // 4. Trả về kết quả
        // Nếu là AJAX -> Trả JSON. Nếu không -> Redirect bình thường.
        if ($this->isAjax()) {
            $this->response(true, "Đã thêm '$name' vào giỏ hàng");
        } else {
            header('Location: /cart');
            exit;
        }
    }

    // Hàm hỗ trợ trả về JSON
    private function response(bool $status, string $message): void {
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }

    public function remove() {
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) die("Invalid CSRF");
        $this->cart->remove((int)$_POST['id']);
        header('Location: /cart');
        exit;
    }

    public function update() {
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) die("Invalid CSRF");
        $this->cart->update((int)$_POST['id'], (int)$_POST['qty']);
        header('Location: /cart');
        exit;
    }
}
