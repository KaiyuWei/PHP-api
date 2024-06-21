<?php

namespace App\Models;

use App\Database;
use PDO;

class Product extends Model
{
    protected $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll(array $queryFields = []): array
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM products";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    public function getById(int $id): array
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price']
        ]);
    }

    // Delete a product by ID
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}