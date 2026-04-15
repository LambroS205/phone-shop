<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Cart;
use App\Core\Database;
use App\Core\Security;
use App\Core\Session;
use PDOException;

class CheckoutController {
    public function process() {
        // 1. Bắt buộc đăng nhập
        if (!Session::has('user_id')) {
            header('Location: /login?redirect=/checkout');
            exit;
        }

        $cartItems = Cart::getItems();
        if (empty($cartItems)) die("Giỏ hàng trống.");

        try {
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction(); // ⚡ Bắt đầu Transaction

            // 2. Kiểm tra tồn kho & tính tổng
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $stmt = $db->prepare("SELECT stock, price FROM products WHERE id = :id");
                $stmt->execute(['id' => $item['id']]);
                $product = $stmt->fetch();

                if (!$product || $product['stock'] < $item['qty']) {
                    throw new \Exception("Sản phẩm {$item['name']} không đủ số lượng hoặc đã hết hàng.");
                }
                $totalAmount += $product['price'] * $item['qty'];
            }

            // 3. Tạo Order
            $stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (:uid, :total, 'pending')");
            $stmt->execute(['uid' => Session::get('user_id'), 'total' => $totalAmount]);
            $orderId = (int)$db->lastInsertId();

            // 4. Tạo Order Items & Trừ kho
            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:oid, :pid, :qty, :price)");
            $stockStmt = $db->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id");

            foreach ($cartItems as $item) {
                $itemStmt->execute(['oid' => $orderId, 'pid' => $item['id'], 'qty' => $item['qty'], 'price' => $item['price']]);
                $stockStmt->execute(['qty' => $item['qty'], 'id' => $item['id']]);
            }

            $db->commit(); // ✅ Thành công -> Commit
            Cart::clear();
            header('Location: /checkout/success');
            exit;

        } catch (\Exception $e) {
            $db->rollBack(); // ❌ Lỗi -> Rollback toàn bộ
            die("Thanh toán thất bại: " . $e->getMessage());
        }
    }

    public function success() {
        require BASE_PATH . '/app/Views/checkout/success.php';
    }
}
