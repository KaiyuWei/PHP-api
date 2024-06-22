<?php

namespace App\Models;

use App\Database;
use PDO;

class Supermarkets extends Model
{
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll(array $queryFields = []): array
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM supermarkets";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM supermarkets WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByName(string $name, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM supermarkets WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO supermarkets (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name']
        ]);
    }

    public function update(array $data): bool
    {
        $sql = "UPDATE supermarkets SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM supermarkets WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}