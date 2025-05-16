<?php

use App\Controllers\AddressController;
use App\Controllers\CartController;
use App\Controllers\ClientController;
use App\Controllers\CouponController;
use App\Controllers\ProductController;
use App\Controllers\PurchaseController;
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

    // Clients
    $router->get('/clients', function () use ($conn) {
        $controller = new ClientController($conn);
        $controller->index();
    });

    $router->post('/clients', function () use ($conn) {
        $controller = new ClientController($conn);
        $controller->store();
    });

    // Addresses
    $router->get('/addresses', function () use ($conn) {
        $controller = new AddressController($conn);
        $controller->index();
    });

    $router->post('/addresses', function () use ($conn) {
        $controller = new AddressController($conn);
        $controller->store();
    });

    // Cart
    $router->get('/cart', callback: function () use ($conn) {
        $controller = new CartController($conn);
        $controller->index();
    });

    $router->post('/cart', function () use ($conn) {
        $controller = new CartController($conn);
        $controller->store();
    });

    $router->delete('/cart/{variation_id}', function ($params) use ($conn) {
        $controller = new CartController($conn);
        $controller->remove($params['variation_id']);
    });

    $router->get('/cart/total', callback: function () use ($conn) {
        $controller = new CartController($conn);
        $controller->total();
    });

    $router->post('/cart/clear', callback: function () use ($conn) {
        $controller = new CartController($conn);
        $controller->clear();
    });

    // Purchase
    $router->get('/purchase', callback: function () use ($conn) {
        $controller = new PurchaseController($conn);
        $controller->index();
    });

    $router->post('/purchase', callback: function () use ($conn) {
        $controller = new PurchaseController($conn);
        $controller->store();
    });
};