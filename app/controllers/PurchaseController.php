<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Address;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Sessions\SessionManager;

class PurchaseController extends Controller
{
    private PurchaseOrder $purchaseOrder;
    private PurchaseOrderItem $purchaseOrderItem;
    private Address $address;
    private Client $client;
    private Coupon $coupon;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->purchaseOrder = new PurchaseOrder($pdo);
        $this->purchaseOrderItem = new PurchaseOrderItem($pdo);
        $this->address = new Address($pdo);
        $this->client = new Client($pdo);
        $this->coupon = new Coupon($pdo);
    }

    public function index()
    {
        try {
            $response = $this->purchaseOrder->findAllWithDetails();
            Response::success("Ordem de compras.", $response);
        } catch (\PDOException $e) {
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $missing = Validator::requiredFields(
                ['address_id', 'client_id'], 
                $data
            );

            if (!empty($missing)) 
            {
                Response::error(
                'Campos obrigatórios ausentes: ' . implode(',', $missing),
                 422
                );
            }

            $cart_items = SessionManager::view();
            if (empty($cart_items))
            {
                Response::error(
                'Carrinho vazio, por favor adicione itens.',
                 400
                );
            }

            $address = $this->address->findById($data['address_id']);
            if (!$address)
            {
                Response::error(
                'Endereço não encontrado.',
                 400
                );
            }

            $client = $this->client->findById($data['client_id']);
            if (!$client)
            {
                Response::error(
                'Cliente não encontrado.',
                 400
                );
            }

            $total_result = SessionManager::total();
            $data['subtotal']       = $total_result['subtotal'];
            $data['freight']        = $total_result['freight'];
            $data['total']          = $total_result['total'];            
            $data['status']         = "pending";

            $coupon = $cart_items["coupon"] ?? null;
            if (
                $coupon &&
                strtotime($coupon["validity"]) >= time()
            )
            {
                $data['coupon_id']      = $coupon["id"];
            }

            $this->pdo->beginTransaction();

            $purchase_order = $this->purchaseOrder->create($data);
            if (!$purchase_order)
            {
                Response::error(
                'Erro ao abrir a ordem da compra.',
                 400
                );
            }
            $purchase_order = $this->purchaseOrder->findById($this->purchaseOrder->lastInsertId());
            
            foreach ($cart_items["items"] as $item) {
                $data_item['purchase_orders_id']    = $purchase_order['id'];
                $data_item['variation_id']          = $item['variation_id'];
                $data_item['quantity']              = $item['quantity'];
                $data_item['price_unity']           = $item['price'];
                $created_item_purchase = $this->purchaseOrderItem->create($data_item);
                if (!$created_item_purchase)
                {
                    Response::error(
                    'Erro ao cadastrar o item na order.',
                    400
                    );
                    return;
                }
            }

            $order_items = $this->purchaseOrderItem->findByOrderId($purchase_order['id']);
            
            $response_data = [
                'order' => [
                    'id'        => $purchase_order['id'],
                    'status'    => $purchase_order['status'],
                    'client_id' => $purchase_order['client_id'],
                    'address_id'=> $purchase_order['address_id'],
                    'subtotal'  => $purchase_order['subtotal'],
                    'freight'   => $purchase_order['freight'],
                    'total'     => $purchase_order['total'],
                    'created_at'=> $purchase_order['created_at'],
                ],
                'items' => array_map(function($item) {
                    return [
                        'variation_id' => $item['variation_id'],
                        'quantity'     => $item['quantity'],
                        'price_unity'  => $item['price_unity'],
                        'total_item'   => $item['total_item'],
                    ];
                }, $order_items),
                "coupon" => $coupon,
                "address" => $address,
                "client" => $client
            ];

            $this->pdo->commit();
            SessionManager::clear();
            Response::success("Pedido finalizado com sucesso!", $response_data);
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            Response::error('Erro no banco de dados: ' . $e->getMessage(), 500);
        }
    }
}