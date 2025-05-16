<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Coupon;

class CouponController extends Controller
{
    private Coupon $coupon;
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->coupon = new Coupon($pdo);
    }
    public function index()
    {
        try {
            $coupons = $this->coupon->findAll();
            Response::success("Lista de Cupons cadastrados.", $coupons);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data['code'])
        {
            $data['code'] = self::generateCode();
        }

        $missing = Validator::requiredFields([
            'code', 'type_discount', 'discount_value', 'validity', 'minimum_subtotal'
        ], $data);
        if (!empty($missing)) 
        {
            Response::error(
                'Campos obrigatórios ausentes: ' . implode(',', $missing),
                 422
            );
        }

        try {
            $created = $this->coupon->create($data);
            if (!$created)
            {
                Response::error(
                'Erro ao criar cupom. Tente novamente.',
                 500
                );
            }
            $coupon = $this->coupon->findById($this->coupon->lastInsertId());
            Response::success('Cupom criado com sucesso!', $coupon);
        } catch (\PDOException $e) {
            return Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    protected function generateCode($tamanho = 10)
    {
        return substr(bin2hex((random_bytes($tamanho))),0, $tamanho);
    }
}