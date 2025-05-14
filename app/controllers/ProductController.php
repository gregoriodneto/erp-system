<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $model = new Product($this->pdo);
        $products = $model->findAll();
        Response::success('Lista de produtos.', $products);
    }
}