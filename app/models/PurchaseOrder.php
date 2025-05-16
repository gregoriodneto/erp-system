<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class PurchaseOrder extends Model
{
    protected string $table = "purchase_orders";

    public function findAllWithDetails(): array
    {
        $statement = $this->pdo->prepare("
            SELECT
                po.*,
                poi.id AS item_id,
                poi.quantity,
                poi.price_unity,
                v.id AS variation_id,
                v.name AS nome_variacao,
                c.id AS client_id,
                c.name AS client_name,
                a.id AS address_id,
                a.street AS address_street,
                cp.id AS coupon_id,
                cp.code AS coupon_code
            FROM purchase_orders po
            LEFT JOIN purchase_order_items poi ON poi.purchase_orders_id = po.id
            LEFT JOIN variations v ON poi.variation_id = v.id
            LEFT JOIN clients c ON po.client_id = c.id
            LEFT JOIN addresses a ON po.address_id = a.id
            LEFT JOIN coupons cp ON po.coupon_id = cp.id
            ORDER BY po.id DESC
        ");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($results as $row) {
            $orderId = $row["id"];

            if (!isset($grouped[$orderId])) 
            {
                $grouped[$orderId] = [
                    'id' => $row['id'],
                    'subtotal' => $row['subtotal'],
                    'freight' => $row['freight'],
                    'total' => $row['total'],
                    'status' => $row['status'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'client' => [
                        'id' => $row['client_id'],
                        'name' => $row['client_name']
                    ],
                    'address' => [
                        'id' => $row['address_id'],
                        'street' => $row['address_street']
                    ],
                    'coupon' => $row['coupon_id'] ? [
                        'id' => $row['coupon_id'],
                        'code' => $row['coupon_code']
                    ] : null,
                    'items' => []
                ];
            }

            if ($row['item_id'])
            {
                $grouped[$orderId]['items'][] = [
                    'id' => $row['item_id'],
                    'variation_id' => $row['variation_id'],
                    'variation_name' => $row['nome_variacao'],
                    'quantity' => $row['quantity'],
                    'price_unity' => $row['price_unity'],
                ];
            }
        }
        return array_values($grouped);
    }
}