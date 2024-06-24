<?php

namespace App\Models;

use App\Helpers\QueryStringCreator;
use App\QueryFilters\GeneralQueryFilter;
use App\QuerySorters\GeneralQuerySorter;
use Exception;
use PDO;

class Supermarket extends Model
{
    const OWNER_TYPE = 'supermarket';

    const QUERIABLE_FIELDS = ['id', 'name'];

    public function __construct() {
        parent::__construct();
    }

    protected function initializeFilterAndSorter(): void
    {
        $this->filter = new GeneralQueryFilter();
        $this->sorter = new GeneralQuerySorter();
    }

    protected function initializeQueriableFields(): void
    {
        $this->queriableFields = self::QUERIABLE_FIELDS;
    }

    public function getAll(array $queryFields = []): array
    {
        $queryFields = QueryStringCreator::convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM supermarkets";
        $statement = $this->db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result ?? [];
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = QueryStringCreator::convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM supermarkets WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getByName(string $name, array $queryFields = [])
    {
        $queryFields = QueryStringCreator::convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM supermarkets WHERE name = :name";
        $statement = $this->db->prepare($sql);
        $statement->execute(['name' => $name]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO supermarkets (name) VALUES (:name)";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            'name' => $data['name']
        ]);
    }

    public function update(array $data): bool
    {
        $sql = "UPDATE supermarkets SET name = :name WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            'id' => $data['id'],
            'name' => $data['name']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM supermarkets WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }

    public function deleteWithOwnedStock(int $id): bool
    {
        try{
            $this->db->beginTransaction();

            (new Stock())->deleteByOwnerIdAndType($id, self::OWNER_TYPE, $this->db);
            $this->delete($id);

            return $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}