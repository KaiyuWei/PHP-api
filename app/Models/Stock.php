<?php

namespace App\Models;

use App\Database;
use PDO;
use Exception;

class Stock extends Model
{
    const OWNER_TYPE_TABLE_MAPPING = [
        'supermarket' => 'supermarkets',
        'wholesaler' => 'wholesalers',
        'outlet' => 'outlets'
    ];

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll(array $queryFields = []): array
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM stock";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM stock WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        if (!$this->validateOwner($data['owner_type'], $data['owner_id'])) {
            throw new Exception('Invalid owner ID for the given owner type.');
        }

        $sql = "INSERT INTO stock (product_id, owner_id, owner_type, quantity, entry_time) 
                VALUES (:product_id, :owner_id, :owner_type, :quantity, :entry_time)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'product_id' => $data['product_id'],
            'owner_id' => $data['owner_id'],
            'owner_type' => $data['owner_type'],
            'quantity' => $data['quantity'],
            'entry_time' => $data['entry_time']
        ]);
    }

    public function update(array $data): bool
    {
        if (!$this->validateOwner($data['owner_type'], $data['owner_id'])) {
            throw new Exception('Invalid owner ID for the given owner type.');
        }

        $sql = "UPDATE stock SET product_id = :product_id, owner_id = :owner_id, owner_type = :owner_type, 
                quantity = :quantity, entry_time = :entry_time WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $data['id'],
            'product_id' => $data['product_id'],
            'owner_id' => $data['owner_id'],
            'owner_type' => $data['owner_type'],
            'quantity' => $data['quantity'],
            'entry_time' => $data['entry_time']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM stock WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function deleteByOwnerIdAndType(int $ownerId, string $ownerType, PDO|null &$pdo): bool
    {
        if(!$pdo) $pdo = $this->db;

        $sql = "DELETE FROM stock WHERE owner_id = :ownerId AND owner_type = :ownerType";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['ownerId' => $ownerId, 'ownerType' => $ownerType]);
    }

    private function validateOwner(string $ownerType, int $ownerId): bool
    {
        if (!$this->isOwnerTypeAllowed($ownerType)) {
            return false;
        }

        return $this->isOwnerExisting($ownerType, $ownerId);
    }

    private function isOwnerTypeAllowed(string $ownerType): bool
    {
        return array_key_exists($ownerType, self::OWNER_TYPE_TABLE_MAPPING);
    }

    private function isOwnerExisting(string $ownerType, int $ownerId): bool
    {
        $table = self::OWNER_TYPE_TABLE_MAPPING[$ownerType];

        $sql = "SELECT COUNT(*) FROM $table WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $ownerId]);
        return $stmt->fetchColumn() > 0;
    }
}