<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Stock;

class StockController extends Controller
{
    private Stock $stock;
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->stock = new Stock($pdo);
    }

    public function index()
    {
        try {
            $stocks = $this->stock->findAll();
            Response::success("Produtos em Estoque.", $stocks);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $missing = Validator::requiredFields(['variation_id', 'quantity'], $data);
            if (!empty($missing))
            {
                Response::error('Campos obrigatórios ausentes: ' . implode(',', $missing), 422);
            }

            $created = $this->stock->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao criar o Estoque. Tente novamente.',
                    500
                );
            }
            $stock = $this->stock->findById($this->stock->lastInsertId());
            Response::success('Produto cadastrado no Estoque com sucesso!', $stock);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function update()
    {
        try {
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

            $existing = $this->stock->findById($id);
            if (!$existing)
            {
                Response::error('Estoque não encontrado.', 404);
            }

            $uploaded = $this->stock->update($id, $data);
            if ($uploaded)
            {
                Response::success('Estoque atualizado com sucesso!', ['id' => $id]);
            }
            else
            {
                Response::error('Erro ao atualizar o estoque.', 500);
            }
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}