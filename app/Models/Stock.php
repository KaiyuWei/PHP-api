<?php

namespace App\Models;

use App\Helpers\QueryStringCreator;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\StockQuerySorter;
use PDO;
use Exception;

class Stock extends Model
{
    const QUERIABLE_FIELDS = ['id', 'product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];

    protected string $tableName;

    const OWNER_TYPE_TABLE_MAPPING = [
        'supermarket' => 'supermarkets',
        'wholesaler' => 'wholesalers',
        'outlet' => 'outlets'
    ];

    public function __construct() {
        parent::__construct();
        $this->tableName = 'stock';
    }

    protected function initializeFilterAndSorter(): void
    {
        $this->filter = new StockQueryFilter();
        $this->sorter = new StockQuerySorter();
    }

    protected function initializeQueriableFields(): void
    {
        $this->queriableFields = self::QUERIABLE_FIELDS;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function queryProductInSuperMarket(int $supermarketId, int $productId, array $queryFields = [], array $orderBy = [])
    {
        $queryFields = QueryStringCreator::convertQueryFieldsToString($queryFields);
        $sql = "SELECT $queryFields FROM stock WHERE owner_type = 'supermarket' AND owner_id = :owner_id AND product_id = :product_id";
        $sql = QueryStringCreator::appendOrderBy($sql, $orderBy);
        $statement = $this->db->prepare($sql);

        $statement->execute([
            'owner_id' => $supermarketId,
            'product_id' => $productId,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalQuantityOfProduct(int $productId, string $ownerType, int $ownerId)
    {
        $sql = "SELECT SUM(quantity) FROM stock WHERE owner_type = :owner_type AND product_id = :product_id AND owner_id = :owner_id";
        $statement = $this->db->prepare($sql);

        $statement->execute([
            'owner_type' => $ownerType,
            'product_id' => $productId,
            'owner_id' => $ownerId,
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO stock (product_id, owner_id, owner_type, quantity, entry_time) 
                VALUES (:product_id, :owner_id, :owner_type, :quantity, :entry_time)";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            'product_id' => $data['product_id'],
            'owner_id' => $data['owner_id'],
            'owner_type' => $data['owner_type'],
            'quantity' => $data['quantity'],
            'entry_time' => $data['entry_time']
        ]);
    }

    public function updateQuantityById(array $data): bool
    {
        $sql = "UPDATE stock SET quantity = :quantity WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            'id' => $data['id'],
            'quantity' => $data['quantity'],
        ]);
    }

    public function update(array $data): bool
    {
        $sql = "UPDATE stock SET product_id = :product_id, owner_id = :owner_id, owner_type = :owner_type, 
                quantity = :quantity, entry_time = :entry_time WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
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
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }

    public function deleteByOwnerIdAndType(int $ownerId, string $ownerType, PDO|null &$pdo): bool
    {
        if(!$pdo) $pdo = $this->db;

        $sql = "DELETE FROM stock WHERE owner_id = :ownerId AND owner_type = :ownerType";
        $statement = $pdo->prepare($sql);
        return $statement->execute(['ownerId' => $ownerId, 'ownerType' => $ownerType]);
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
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $ownerId]);
        return $statement->fetchColumn() > 0;
    }
}