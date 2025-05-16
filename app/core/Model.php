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
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $statement = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE `id` = :id");
            $statement->execute(["id"=> $id]);
            $data = $statement->fetch(PDO::FETCH_ASSOC);

            return $data ?: null;
        } catch (\PDOException $e) {
            throw $e;
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
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $set = implode(", ", array_map(fn($key) => "`$key` = :$key", array_keys($data)));
            $data['id'] = $id;

            $sql = sprintf("UPDATE `%s` SET %s WHERE `id` = :id", $this->table, $set);
            $statement = $this->pdo->prepare($sql);
            return $statement->execute($data);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare( "DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute(['id' => $id]);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}