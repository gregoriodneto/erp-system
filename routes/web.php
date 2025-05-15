<?php

use App\Controllers\CouponController;
use App\Controllers\ProductController;
use App\Controllers\StockController;
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

    // Stocks
    $router->get('/stocks', function () use ($conn) {
        $controller = new StockController($conn);
        $controller->index();
    });

    $router->post('/stocks', function () use ($conn) {
        $controller = new StockController($conn);
        $controller->store();
    });

    $router->put('/stocks', function () use ($conn) {
        $controller = new StockController($conn);
        $controller->update();
    });

    // Coupons
    $router->get('/coupons', function () use ($conn) {
        $controller = new CouponController($conn);
        $controller->index();
    });

    $router->post('/coupons', function () use ($conn) {
        $controller = new CouponController($conn);
        $controller->store();
    });
};