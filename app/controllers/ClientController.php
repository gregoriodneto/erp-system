<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $model = new Client($this->pdo);
        
        try {
            $clients = $model->findAll();
            Response::success("Lista de clientes cadastrados.", $clients);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $missing = Validator::requiredFields([
            'name', 'email'
        ], $data);
        if (!empty($missing)) 
        {
            Response::error(
                'Campos obrigatórios ausentes: ' . implode(',', $missing),
                 422
            );
        }

        $model = new Client($this->pdo);
        
        try {
            $created = $model->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao cadastrar o Cliente. Tente novamente.',
                 500
                );
            }
            Response::success('Cliente cadastrado com sucesso!', $created);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}