<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Cart;
use App\Core\Security;

class CartController {
    public function index() {
        require BASE_PATH . '/app/Views/cart/index.php';
    }

    public function add() {
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) die("Invalid CSRF");

        // Lấy dữ liệu từ POST (thực tế nên validate kỹ hơn)
        $id = (int)$_POST['id'];
        $name = htmlspecialchars($_POST['name']);
        $price = (float)$_POST['price'];
        $image = htmlspecialchars($_POST['image']);

        Cart::add($id, $name, $price, $image, 1);
        header('Location: /cart');
        exit;
    }

    public function remove() {
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) die("Invalid CSRF");
        Cart::remove((int)$_POST['id']);
        header('Location: /cart');
        exit;
    }

    public function update() {
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) die("Invalid CSRF");
        Cart::update((int)$_POST['id'], (int)$_POST['qty']);
        header('Location: /cart');
        exit;
    }
}
