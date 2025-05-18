<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Stock extends Model
{
    protected string $table = "stocks";

    public function findStockByVariationId($variation_id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE variation_id = :id");
        $statement->execute(["id" => $variation_id]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
}