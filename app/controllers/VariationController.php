<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Variation;

class VariationController extends Controller
{
    private Variation $variation;

    public function __construct($pdo) 
    {   
        parent::__construct($pdo);
        $this->variation = new Variation($pdo);
    }
    public function index()
    {
        try {
            $variations = $this->variation->findAll();
            Response::success('Lista de variações',$variations);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function getById($id)
    {
        try {
            $variation = $this->variation->findById($id);
            if (!$variation) {
                Response::error('Variação de produto não encontrado.', 404);
            }
            Response::success('Lista de variações',$variation);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {
        try {
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

            $created = $this->variation->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao criar a variação do produto. Tente novamente.',
                    500
                );
            }
            $variation = $this->variation->findById($this->variation->lastInsertId());
            Response::success('Variação do produto criado com sucesso!',$variation);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}