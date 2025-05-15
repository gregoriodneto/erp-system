<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Address;

class AddressController extends Controller
{
    public function index()
    {
        $model = new Address($this->pdo);
        
        try {
            $addresses = $model->findAll();
            Response::success("Lista de endereços cadastrados.", $addresses);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $missing = Validator::requiredFields([
            'cep', 'street', 'district', 'city', 'state'
        ], $data);
        if (!empty($missing)) 
        {
            Response::error(
                'Campos obrigatórios ausentes: ' . implode(',', $missing),
                 422
            );
        }

        $model = new Address($this->pdo);
        
        try {
            $created = $model->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao cadastrar o Endereço. Tente novamente.',
                 500
                );
            }
            Response::success('Endereço cadastrado com sucesso!', $created);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}