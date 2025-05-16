<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Address;
use App\Services\CepService;
use Exception;

class AddressController extends Controller
{
    private CepService $cepService;
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->cepService = new CepService();
    }
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

        $data = $this->validate($data);

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

    protected function validate($data)
    {
        $autoFillFields = ['cep', 'street', 'district', 'city', 'state'];
        $needAutoFill = false;

        foreach ($autoFillFields as $field) {
            if (empty($data[$field])) 
            {
                $needAutoFill = true;
                break;
            }
        }

        if ($needAutoFill && !empty($data['cep']))
        {            
            $cepData = $this->cepService->searchAddress($data['cep']);
            $data['street']     ??= $cepData['logradouro'];
            $data['district']   ??= $cepData['bairro'];
            $data['city']       ??= $cepData['localidade'];
            $data['state']      ??= $cepData['uf'];
        }

        return $data;
    }
}