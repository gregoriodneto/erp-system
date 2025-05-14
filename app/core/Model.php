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

    public function findById(int $id): ?array
    {
        try {
            $statement = $this->pdo->query("SELECT * FROM {$this->table} WHERE id = :id");
            $statement->execute(["id"=> $id]);
            $data = $statement->fetch(PDO::FETCH_ASSOC);

            return $data ?: null;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function create(array $data): bool
    {
        try {
            $fields = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
            $statement = $this->pdo->prepare($sql);
            return $statement->execute($data);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $set = implode(",", array_map(fn($key) => "$key = :$key", array_keys($data)));
            $data['id'] = $id;

            $sql = "UPDATE {$this->table} SET $set WHERE id = :id";
            $statement = $this->pdo->prepare($sql);
            return $statement->execute($data);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare( "DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute(['id' => $id]);
    }
}