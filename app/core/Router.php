<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler, array $middlewares = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => rtrim($path, '/') ?: '/',
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim(parse_url($uri, PHP_URL_PATH) ?? '/', '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            if ($route['path'] !== $path) {
                continue;
            }

            foreach ($route['middlewares'] as $middleware) {
                $middleware();
            }

            call_user_func($route['handler']);
            return;
        }

        http_response_code(404);
        echo view('partials/404');
    }
}
