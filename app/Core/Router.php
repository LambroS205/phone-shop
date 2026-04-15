<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Session;
use App\Core\Security;
use App\Core\Container; // Import Container

class Router {
    private array $routes = [];
    private array $middlewareQueue = [];
    private Container $container; // 🔒 Property chứa Container

    // Inject Container qua Constructor
    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function add(string $uri, string $method, string $controllerClass, string $methodAction, array $middlewares = []): void {
        $this->routes[$method][$uri] = ['controller' => $controllerClass, 'action' => $methodAction];
        $this->middlewareQueue[$method][$uri] = $middlewares;
    }

    public function resolve(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // 1. Chạy Middleware
        $middlewares = $this->middlewareQueue[$method][$uri] ?? [];
        foreach ($middlewares as $middleware) {
            // Check if middleware has parameters (array format: [MiddlewareClass, param1, param2...])
            if (is_array($middleware)) {
                $middlewareClass = array_shift($middleware);
                $instance = new $middlewareClass(...$middleware);
            } else {
                $instance = new $middleware();
            }
            if (!$instance->handle()) {
                return;
            }
        }

        // 2. Dispatch Controller thông qua Container
        $route = $this->routes[$method][$uri] ?? null;
        if ($route) {
            // ✅ THAY ĐỔI LỚN: Không dùng new, mà dùng Container
            $controller = $this->container->make($route['controller']);

            // Gọi action
            $action = $route['action'];
            $controller->{$action}();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
