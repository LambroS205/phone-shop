<?php
declare(strict_types=1);
define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/Core/Autoloader.php';
App\Core\Autoloader::register();

use App\Core\Router;
use App\Core\Session;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

Session::init();
// 🔒 Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;");
$router = new Router();

// Public Routes
$router->add('/', 'GET', HomeController::class, 'index');
$router->add('/login', 'GET', AuthController::class, 'showLogin');
$router->add('/login', 'POST', AuthController::class, 'login');

// Protected Routes (Ví dụ)
// $router->add('/admin', 'GET', AdminController::class, 'index', [new AuthMiddleware(), new RoleMiddleware('admin')]);
// Thêm vào public/index.php (trước $router->resolve())

use App\Controllers\ProductController;

// Public
$router->add('/', 'GET', ProductController::class, 'index');
// (Xóa route Home cũ hoặc gộp vào đây)

// Admin Routes (Giả lập để test logic CRUD)
$router->add('/admin/products/add', 'POST', ProductController::class, 'store');
$router->add('/admin/products/delete', 'POST', ProductController::class, 'destroy');
use App\Controllers\CartController;
use App\Controllers\CheckoutController;

// Cart Routes
$router->add('/cart', 'GET', CartController::class, 'index');
$router->add('/cart/add', 'POST', CartController::class, 'add');
$router->add('/cart/remove', 'POST', CartController::class, 'remove');
$router->add('/cart/update', 'POST', CartController::class, 'update');

$router->add('/checkout/process', 'POST', CheckoutController::class, 'process');
$router->add('/checkout/success', 'GET', CheckoutController::class, 'success');
$router->resolve();
