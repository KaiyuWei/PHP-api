<?php

namespace App\Helpers;

use App\QueryFilters\QueryFilter;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\QuerySorter;

class QueryStringCreator
{
    public function __construct(
        protected QueryFilter $filter,
        protected QuerySorter $sorter)
    {
    }

    public function createSelectQuery(
        string $tableName,
        array $queryFields = [],
        array $filters = [],
        array $orderBys = [],
        int $limit = 0,
        int $offset = 0)
    {
        $selectClause = $this->createSelectClause($tableName, $queryFields);
        $whereClause = $this->filter->createWhereClause($filters);
        $sortByClause = $this->sorter->createOrderByClause($orderBys);
        $limitAndOffsetClause = $this->createLimitAndOffsetClause($limit, $offset);
    }

    public function createSelectClause(string $tableName, array $queryFields = []): string
    {
        $queryFieldsString = $this->convertQueryFieldsToString($queryFields);
        return 'SELECT ' . $queryFieldsString . ' FROM ' . $tableName;
    }

    public function convertQueryFieldsToString(array $queryFields): string
    {
        return empty($queryFields) ? '*' : implode(', ', $queryFields);
    }

    public function createLimitAndOffsetClause(int $limit, int $offset): string
    {
        if(!$limit) return '';

        $limitString = "LIMIT $limit";
        $offsetString = $offset ? " OFFSET $offset" : "";
        return sprintf('%s%s', $limitString, $offsetString);
    }
}