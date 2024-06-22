<?php

namespace App\QueryFilters;

class StockQueryFilter extends QueryFilter
{
    const FILTERABLE_COLUMNS = ['product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
    public function __construct()
    {
        parent::__construct();
        $this->columns = self::FILTERABLE_COLUMNS;
    }

    public function buildWhereClause(array $filters): string
    {
        $whereClauseComponents = $this->createWhereClauseComponentArray($filters);
        return $this->createWhereClause($whereClauseComponents);
    }

    private function createWhereClauseComponentArray(array $filters): array
    {
        $whereClauseComponents = [];
        foreach($this->columns as $column)
        {
            if (!empty($filters[$column])) {
                $whereClauseComponents[] = sprintf('%s = :%s', $column, $column);
            }
        }
        return $whereClauseComponents;
    }

    private function createWhereClause(array $whereClauseComponents): string
    {
        $whereClause = '';
        if (!empty($whereClauseComponents)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereClauseComponents);
        }
        return $whereClause;
    }
}