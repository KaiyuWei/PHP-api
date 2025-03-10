<?php

namespace App\Services;

use App\Helpers\CacheHelper;
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
        list('recordPerPage' => $recordPerPage, 'offset' => $offset) = $this->getPaginationData($page, $recordPerPage);
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

        $cacheHelper = (new CacheHelper());
        $cacheKey = $this->model->generateCacheKeyByQueryAndParams($sql, $params);
        $cache = $cacheHelper->getCache($cacheKey);
        if ($cache) return $cache;

        $queryResult = $this->model->executeSqlQuery($sql, $params);

        $cacheHelper->setCache($cacheKey, $queryResult);
        return $queryResult;
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

    private function getPaginationData(int $page, int $recordPerPage): array
    {
        if ($recordPerPage) {
            // $recordPerPage provided, $page not: first page by default
            $page = $page ? $page : 1;
            $offset = ($page - 1) * $recordPerPage;
        }
        else {
            if($page) {
                // $page provided, $recordPerPage not: 10 records by default
                $recordPerPage = 10;
                $offset = ($page - 1) * $recordPerPage;;
            }
            else{
                $recordPerPage = 0;
                $offset = 0;
            }
        }

        return ['recordPerPage' => $recordPerPage, 'offset' => $offset];
    }
}