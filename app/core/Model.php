<?php
namespace App\Core;

use PDO;
use App\Config\Database;

class Model 
{
    protected PDO $pdo;
    protected string $table;

    public function __construct($conn) 
    {
        $this->pdo = $conn;
    }

    public function findAll(): array
    {
        try {
            $statement = $this->pdo->query("SELECT * FROM {$this->table}");
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}