<?php

use App\Controllers\ProductController;
use App\Controllers\VariationController;
use App\Core\Router;

/**
 * Define as rotas da aplicação
 * 
 * @param App\Core\Router $router
 * @param PDO $conn
 */
return function($router, $conn)
{
    // Products
    $router->get('/products', function () use ($conn) {
        $controller = new ProductController($conn);
        $controller->index();
    });

    $router->post('/products', function () use ($conn) {
        $controller = new ProductController($conn);
        $controller->store();
    });

    // Variations
    $router->get('/variations', function () use ($conn) {
        $controller = new VariationController($conn);
        $controller->index();
    });

    $router->post('/variations', function () use ($conn) {
        $controller = new VariationController($conn);
        $controller->store();
    });
};