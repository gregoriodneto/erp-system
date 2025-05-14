<?php

use App\Controllers\ProductController;
use App\Core\Router;

/**
 * Define as rotas da aplicação
 * 
 * @param App\Core\Router $router
 * @param PDO $conn
 */
return function($router, $conn)
{
    $router->get('/products', function () use ($conn) {
        $controller = new ProductController($conn);
        $controller->index();
    });
};