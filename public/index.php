<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/Core/Autoloader.php';
App\Core\Autoloader::register();

use App\Core\Router;
use App\Core\Session;
use App\Core\Container;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\AuthController;
use App\Models\Product;

Session::init();

// 1. Khởi tạo Container
$container = new Container();

// 2. Bind Models
$container->bind(Product::class, function($c) {
    return new Product();
});

// 3. Bind Controllers (Inject Dependencies)
$container->bind(ProductController::class, function($c) {
    return new ProductController($c->make(Product::class));
});

$container->bind(CartController::class, function($c) {
    return new CartController();
});

$container->bind(CheckoutController::class, function($c) {
    return new CheckoutController();
});

$container->bind(AuthController::class, function($c) {
    return new AuthController();
});

// 4. Khởi tạo Router với Container
$router = new Router($container);

// 5. Định nghĩa Routes (Đảm bảo không thiếu route nào)

// Product Routes
$router->add('/', 'GET', ProductController::class, 'index');

// Auth Routes
$router->add('/login', 'GET', AuthController::class, 'showLogin');
$router->add('/login', 'POST', AuthController::class, 'login');
$router->add('/logout', 'GET', AuthController::class, 'logout');

// Cart Routes
$router->add('/cart', 'GET', CartController::class, 'index');
$router->add('/cart/add', 'POST', CartController::class, 'add');
$router->add('/cart/remove', 'POST', CartController::class, 'remove');
$router->add('/cart/update', 'POST', CartController::class, 'update');

// Checkout Routes
$router->add('/checkout/process', 'POST', CheckoutController::class, 'process');
$router->add('/checkout/success', 'GET', CheckoutController::class, 'success');

// 6. Resolve Request
$router->resolve();
