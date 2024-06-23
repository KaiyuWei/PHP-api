<?php

namespace App\Models;

use App\Database;
use App\QueryFilters\GeneralQueryFilter;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\GeneralQuerySorter;
use App\QuerySorters\StockQuerySorter;
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

        $sql = "SELECT " . $queryFields . " FROM stock";
        $statement = $this->db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    public function getAllWith(array $filters, array $orderBys, $limit, $offset) {

        $whereClause = (new StockQueryFilter())->createWhereClause($filters);

//        foreach($this->columns as $column)
//        {
//            if (!empty($filters[$column])) {
//                $paramKey = ':' . $column;
//                $params[$paramKey] = $filters[$column];
//            }
//        }

        // Sorting
        $orderByClause = (new StockQuerySorter())->createOrderByClause($orderBys);

        // Pagination
        $limit = intval($limit);
        $offset = intval($offset);

        // Construct the SQL query
        $sql = "SELECT * FROM stock $whereClause $orderByClause LIMIT :limit OFFSET :offset";

        // Prepare and execute the statement
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

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