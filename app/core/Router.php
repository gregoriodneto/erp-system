<?php

namespace App\Core;

use App\Core\Helpers\Response;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $callback)
    {
        $this->addRoute("GET", $uri, $callback);
    }

    public function post(string $uri, callable $callback)
    {
        $this->addRoute("POST", $uri, $callback);
    }

    public function put(string $uri, callable $callback)
    {
        $this->addRoute("PUT", $uri, $callback);
    }

    public function delete(string $uri, callable $callback)
    {
        $this->addRoute("DELETE", $uri, $callback);
    }

    public function patch(string $uri, callable $callback)
    {
        $this->addRoute("PATCH", $uri, $callback);
    }

    public function addRoute(string $method, string $uri, callable $callback)
    {
        $this->routes[$method . $uri] = $callback;
    }

    public function dispatch(string $method, string $uri)
    {
        foreach ($this->routes as $routeKey => $callback) {
            [$routeMethod, $routePath] = explode("/", $routeKey, 2);
            $routeMethod = strtoupper($routeMethod);

            if ($routeMethod !== $method)
            {
                continue;
            }

            $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $routePath);
            $pattern = "#^$pattern$#";

            if (preg_match($pattern, ltrim($uri, '/'), $matches))
            {
                array_shift($matches);

                preg_match_all('#\{([^}]+)\}#', $routePath, $paramNames);
                $params = [];
                foreach ($paramNames[1] as $index => $name) {
                    $params[$name] = $matches[$index] ?? null;
                }
                return $callback($params);
            }
        }
        http_response_code(404);
        Response::error("Rota não encontrada", 404);
    }
}