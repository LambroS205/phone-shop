<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/Core/Autoloader.php';
App\Core\Autoloader::register();

use App\Core\Router;
use App\Core\Session;
use App\Core\Container;
use App\Controllers\ProductController;
use App\Controllers\AdminProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\AuthController;
use App\Models\Product;
use App\Models\User;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

Session::init();

// 1. Khởi tạo Container
$container = new Container();

// 2. Bind Models
$container->bind(Product::class, function($c) {
    return new Product();
});

$container->bind(User::class, function($c) {
    return new User();
});

// 3. Bind Controllers (Inject Dependencies)
$container->bind(ProductController::class, function($c) {
    return new ProductController($c->make(Product::class));
});

$container->bind(\App\Controllers\AdminProductController::class, function($c) {
    return new \App\Controllers\AdminProductController($c->make(Product::class));
});

$container->bind(CartController::class, function($c) {
    return new CartController(new \App\Core\Cart());
});

$container->bind(CheckoutController::class, function($c) {
    return new CheckoutController(new \App\Core\Cart());
});

$container->bind(AuthController::class, function($c) {
    return new AuthController($c->make(User::class));
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

// Admin Product Routes
$router->add('/admin/products', 'GET', AdminProductController::class, 'index', [AuthMiddleware::class, RoleMiddleware::class]);
$router->add('/admin/products/create', 'GET', AdminProductController::class, 'create', [AuthMiddleware::class, RoleMiddleware::class]);
$router->add('/admin/products/store', 'POST', AdminProductController::class, 'store', [AuthMiddleware::class, RoleMiddleware::class]);
$router->add('/admin/products/edit', 'GET', AdminProductController::class, 'edit', [AuthMiddleware::class, RoleMiddleware::class]);
$router->add('/admin/products/update', 'POST', AdminProductController::class, 'update', [AuthMiddleware::class, RoleMiddleware::class]);
$router->add('/admin/products/delete', 'POST', AdminProductController::class, 'destroy', [AuthMiddleware::class, RoleMiddleware::class]);

// 6. Resolve Request
$router->resolve();
