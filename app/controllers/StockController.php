<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $model = new Stock($this->pdo);
        $stocks = $model->findAll();
        Response::success("Produtos em Estoque.", $stocks);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $missing = Validator::requiredFields(['variation_id', 'quantity'], $data);
        if (!empty($missing))
        {
            Response::error('Campos obrigatórios ausentes: ' . implode(',', $missing), 422);
        }

        $model = new Stock($this->pdo);
        $created = $model->create($data);

        Response::success('Produto cadastrado no Estoque com sucesso!', $created);
    }

    public function update()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id']))
        {
            Response::error('ID do estoque é obrigatório.',400);
        }

        $id = $data['id'];

        $missing = Validator::requiredFields(['variation_id', 'quantity'], $data);
        if (!empty($missing))
        {
            Response::error('Campos obrigatórios ausentes: ' . implode(',', $missing), 422);
        }

        $model = new Stock($this->pdo);

        $existing = $model->findById($id);
        if (!$existing)
        {
            Response::error('Estoque não encontrado.', 404);
        }

        $uploaded = $model->update($id, $data);
        if ($uploaded)
        {
            Response::success('Estoque atualizado com sucesso!', ['id' => $id]);
        }
        else
        {
            Response::error('Erro ao atualizar o estoque.', 500);
        }
    }
}