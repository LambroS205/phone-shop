<?php
declare(strict_types=1);
namespace App\Core;

use App\Core\Session;
use App\Core\Security;

class Router {
    private array $routes = [];
    private array $middlewareQueue = [];

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
            $instance = new $middleware();
            if (!$instance->handle()) {
                return; // Dừng nếu middleware chặn request
            }
        }

        // 2. Dispatch Controller
        $route = $this->routes[$method][$uri] ?? null;
        if ($route) {
            $controller = new $route['controller']();
            $controller->{$route['action']}();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
