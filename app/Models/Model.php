<?php

namespace App\Models;

use App\Database;
use App\QueryFilters\QueryFilter;
use App\QuerySorters\QuerySorter;
use PDO;
use PDOStatement;

abstract class Model
{
    protected PDO $db;

    protected QueryFilter $filter;

    protected QuerySorter $sorter;

    protected array $queriableFields;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->initializeFilterAndSorter();
        $this->initializeQueriableFields();
    }

    abstract protected function initializeFilterAndSorter(): void;

    abstract protected function initializeQueriableFields(): void;

    protected function bindValueToStatement(PDOStatement &$statement, array $params): void
    {
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
    }

    protected function getAllowedQueryFields(array $requestedFields): array
    {
        if (!$requestedFields) return $this->queriableFields;
        return array_intersect($requestedFields, $this->queriableFields);
    }
}