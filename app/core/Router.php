<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $callback)
    {
        $this->addRoute("GET", $uri, $callback);
    }

    public function addRoute(string $method, string $uri, callable $callback)
    {
        $this->routes[$method . $uri] = $callback;
    }

    public function dispatch(string $method, string $uri)
    {
        $key = $method . $uri;
        if (isset($this->routes[$key])) 
        {
            $this->routes[$key]();
        } 
        else 
        {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found.']);
        }
    }
}