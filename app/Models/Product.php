<?php

namespace App\Models;

use App\QueryFilters\GeneralQueryFilter;
use App\QuerySorters\GeneralQuerySorter;
use PDO;

class Product extends Model
{
    public function __construct() {
        parent::__construct();
    }

    protected function initializeFilterAndSorter(): void
    {
        $this->filter = new GeneralQueryFilter();
        $this->sorter = new GeneralQuerySorter();
    }

    public function getAll(array $queryFields = []): array
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM products";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByName(string $name, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM products WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO products (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name']
        ]);
    }

    public function update(array $data): bool
    {
        $sql = "UPDATE products SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}