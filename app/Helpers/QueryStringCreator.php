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

    public function createSelectQuery(array $filters, array $orderBys, int $limit, int $offset)
    {
        $whereClause = $this->filter->createWhereClause($filters);
    }
}