<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Client;

class ClientController extends Controller
{
    private Client $client;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->client = new Client($pdo);
    }

    public function index()
    {
        try {
            $clients = $this->client->findAll();
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
        
        try {
            $created = $this->client->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao cadastrar o Cliente. Tente novamente.',
                 500
                );
            }
            $client = $this->client->findById($this->client->lastInsertId());
            Response::success('Cliente cadastrado com sucesso!', $client);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}