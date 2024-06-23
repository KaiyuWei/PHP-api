<?php

namespace App\Models;

use App\Helpers\QueryStringCreator;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\StockQuerySorter;
use PDO;
use Exception;

class Stock extends Model
{
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

    public function getAll(array $queryFields = []): array
    {
        $queryFields = QueryStringCreator::convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM stock";
        $statement = $this->db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    //@todo: rename the function
    public function getAllWith(
        array $queryFields = [],
        array $filters = [],
        array $orderBys = [],
        int $limit = 0,
        int $offset = 0)
    {
        $queryCreator = new QueryStringCreator($this->filter, $this->sorter);
        $sql = $queryCreator->createSelectQuery($this->tableName, $queryFields, $filters, $orderBys, $limit, $offset);
        $params = $queryCreator->createValueBindingArray($filters);

        $statement = $this->db->prepare($sql);
        $this->bindValueToStatement($statement, $params);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = QueryStringCreator::convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM stock WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        if (!$this->validateOwner($data['owner_type'], $data['owner_id'])) {
            throw new Exception('Invalid owner ID for the given owner type.');
        }

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

    public function update(array $data): bool
    {
        if (!$this->validateOwner($data['owner_type'], $data['owner_id'])) {
            throw new Exception('Invalid owner ID for the given owner type.');
        }

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