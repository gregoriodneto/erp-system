<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    private Product $product;
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->product = new Product($pdo);
    }

    public function index()
    {        
        try {
            $products = $this->product->findAll();
            Response::success('Lista de produtos.', $products);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {        
        try {
            $data = json_decode(file_get_contents(  'php://input'), true);

            $missing = Validator::requiredFields(['name', 'price_base'], $data);
            if (!empty($missing)) 
            {
                Response::error('Campos obrigatórios ausentes:' . implode(', ', $missing), 422);
            }

            $model = new Product($this->pdo);
            $created = $model->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao criar o produto. Tente novamente.',
                    500
                );
            }
            $product = $this->product->findById($this->product->lastInsertId());
            Response::success('Produto criado com sucesso!', $product);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}