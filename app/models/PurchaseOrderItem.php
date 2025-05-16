<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class PurchaseOrderItem extends Model
{
    protected string $table = "purchase_order_items";

    public function findByOrderId($order_id): array
    {
        $statement = $this->pdo->prepare("
            SELECT
                poi.id,
                poi.purchase_orders_id,
                poi.variation_id,
                v.name AS variation_name,
                poi.quantity,
                poi.price_unity,
                (poi.price_unity * poi.quantity) AS total_item
            FROM
                purchase_order_items poi
            JOIN
                variations v ON v.id = poi.variation_id
            WHERE 
                poi.purchase_orders_id = :id
        ");
        $statement->execute(["id"=> $order_id]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}