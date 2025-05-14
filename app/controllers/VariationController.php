<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Variation;

class VariationController extends Controller
{
    public function index()
    {
        $model = new Variation($this->pdo);
        $variations = $model->findAll();
        Response::success('Lista de variações',$variations);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'),true);
        $missing = Validator::requiredFields([
            'name',
            'product_id',
            'additional_price'
        ], $data);
        if (!empty($missing))
        {
            Response::error('Campos obrigatórios ausentes: ' . implode(',', $missing),422);
        }

        $model = new Variation($this->pdo);
        $created = $model->create($data);

        Response::success('Variação do produto criado com sucesso!',$created);
    }
}