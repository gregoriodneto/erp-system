<?php

namespace App\Webhook;

use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Middlewares\AuthWebhook;
use App\Models\PurchaseOrder;
use Exception;
use PDO;

class WebhookStatusPurchase
{
    private PDO $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function status()
    {
        try {
            AuthWebhook::verity();

            $data = json_decode(file_get_contents("php://input"), true);

            $missing = Validator::requiredFields([
                'purchase_id', 'status'
            ], $data);
            if (!empty($missing)) 
            {
                Response::error(
                    'Campos obrigatórios ausentes: ' . implode(',', $missing),
                    422
                );
            }

            if (!self::verifyStatus($data['status']))
            {
                Response::error(
                    'O status informado não é um dos permitidos. Consultar /webhook/status-consult', 
                    422
                );
            }

            $purchaseModel = new PurchaseOrder($this->pdo);
            $purchase = $purchaseModel->findById($data['purchase_id']);
            if (!$purchase)
            {
                Response::error('Pedido não encontrado.', 404);   
            }
            $purchase["status"] = $data["status"];
            $purchaseModel->update($purchase["id"], $purchase);

            Response::success("Status do Pedido atualizado com sucesso!", $purchase);
        } catch (Exception $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function getStatus()
    {
        try {
            AuthWebhook::verity();
            Response::success(
                'Listagem dos status disponíveis.',
                [
                    "status" => ["pending","canceled", "delivered", "paid"],
                ]
            );
        } catch (Exception $e) {
            Response::error('Erro na consulta dos status: ' . $e->getMessage(), 500);
        }
    }

    protected static function verifyStatus($status)
    {
        $allowed = ["pending", "canceled", "delivered", "paid"];
        return in_array($status, $allowed);
    }
}