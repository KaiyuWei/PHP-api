<?php

namespace App\Helpers;

use App\QueryFilters\QueryFilter;
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

        return $this->combineClausesToQueryString($selectClause, $whereClause, $sortByClause, $limitAndOffsetClause);
    }

    public static function convertQueryFieldsToString(array $queryFields): string
    {
        return empty($queryFields) ? '*' : implode(', ', $queryFields);
    }

    public function createValueBindingArray(array $filters): array
    {
        $allowedFilters = $this->filter->getFilterableColumns();
        $params = [];

        foreach($allowedFilters as $filterName)
        {
            if (!empty($filters[$filterName])) {
                $paramKey = ':' . $filterName;
                $params[$paramKey] = $filters[$filterName];
            }
        }

        return $params;
    }

    public function createSelectClause(string $tableName, array $queryFields = []): string
    {
        $queryFieldsString = self::convertQueryFieldsToString($queryFields);
        return 'SELECT ' . $queryFieldsString . ' FROM ' . $tableName;
    }

    public function createLimitAndOffsetClause(int $limit, int $offset): string
    {
        if(!$limit) return '';

        $limitString = "LIMIT $limit";
        $offsetString = $offset ? " OFFSET $offset" : "";
        return sprintf('%s%s', $limitString, $offsetString);
    }

    protected function combineClausesToQueryString(
        string $select,
        string $where,
        string $sortBy,
        string $limitAndOffset
    ): string
    {
        $query = $select;

        if($where) $query = "$query $where";
        if($sortBy) $query = "$query $sortBy";
        if($limitAndOffset) $query = "$query $limitAndOffset";

        return $query . ';';
    }
}