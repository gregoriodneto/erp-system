<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;

class PurchaseController extends Controller
{
    private PurchaseOrder $purchaseOrder;
    private PurchaseOrderItem $purchaseOrderItem;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->purchaseOrder = new PurchaseOrder($pdo);
        $this->purchaseOrderItem = new PurchaseOrderItem($pdo);
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
        
    }
}