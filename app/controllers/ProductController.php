<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $model = new Product($this->pdo);
        $products = $model->findAll();
        Response::success('Lista de produtos.', $products);
    }

    public function store()
    {
        $data = json_decode(file_get_contents(  'php://input'), true);

        $missing = Validator::requiredFields(['name', 'price_base'], $data);
        if (!empty($missing)) 
        {
            Response::error('Campos obrigatórios ausentes:' . implode(', ', $missing), 422);
        }

        $model = new Product($this->pdo);
        $created = $model->create($data);

        Response::success('Produto criado com sucesso!', $created);
    }
}