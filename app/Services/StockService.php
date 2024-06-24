<?php

namespace App\Services;

use App\Helpers\DynamicRouteParser;
use App\Helpers\QueryStringCreator;
use App\Models\Stock;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\StockQuerySorter;

class StockService extends Service
{
    public function __construct() {
        $this->model = new Stock();
    }

    public function getAllWithOptionsAndPagination(
        array $queryFields = [],
        array $filters = [],
        array $orderBys = [],
        int $page = 0,
        int $recordPerPage = 10)
    {
        if ($recordPerPage) {
            $page = $page ? $page : 1;
            $offset = ($page - 1) * $recordPerPage;
        }
        else {
            $recordPerPage = 0;
            $offset = 0;
        }

        return $this->getAllWithOptions($queryFields, $filters, $orderBys, $recordPerPage, $offset);
    }

    public function getStock(string $ownerType)
    {
        $ownerId = DynamicRouteParser::parseAndGetDigitsAtTheEndOfUri($_SERVER['REQUEST_URI']);
        $filters = [
            'owner_type' => $ownerType,
            'owner_id' => $ownerId
        ];

        return $this->getAllWithOptionsAndPagination([], $filters);
    }

    public function getAllWithOptions(
        array $queryFields = [],
        array $filters = [],
        array $orderBys = [],
        int $limit = 0,
        int $offset = 0)
    {
        $sql = $this->createSqlQuery($queryFields, $filters, $orderBys, $limit, $offset);
        $params = $this->createValueBindingArray($filters);

        return $this->model->executeSqlQuery($sql, $params);
    }

    protected function createQueryCreator(): QueryStringCreator
    {
        $filter = new StockQueryFilter();
        $sorter = new StockQuerySorter();
        return new QueryStringCreator($filter, $sorter);
    }

    private function createSqlQuery(array $queryFields, array $filters, array $orderBys, int $limit, int $offset): string
    {
        $queryCreator = $this->createQueryCreator();
        $allowedQueryFields = $this->model->getAllowedQueryFields($queryFields);
        $tableName = $this->model->getTableName();

        return $queryCreator->createSelectQuery($tableName, $allowedQueryFields, $filters, $orderBys, $limit, $offset);
    }

    private function createValueBindingArray(array $filters): array
    {
        $queryCreator = $this->createQueryCreator();
        return $queryCreator->createValueBindingArray($filters);
    }
}