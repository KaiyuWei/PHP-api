<?php

namespace App\QueryFilters;

class StockQueryFilter extends QueryFilter
{
    const FILTERABLE_COLUMNS = ['product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
    public function __construct()
    {
        parent::__construct();
        $this->filterableColumns = self::FILTERABLE_COLUMNS;
    }

    public function createWhereClause(array $filters): string
    {
        $whereClauseComponents = $this->s($filters);
        return $this->createWhereClauseFromComponents($whereClauseComponents);
    }

    private function s(array $filters): array
    {
        $whereClauseComponents = [];
        foreach($this->filterableColumns as $column)
        {
            if (!empty($filters[$column])) {
                $whereClauseComponents[] = sprintf('%s = :%s', $column, $column);
            }
        }
        return $whereClauseComponents;
    }

    private function createWhereClauseFromComponents(array $whereClauseComponents): string
    {
        $whereClause = '';
        if (!empty($whereClauseComponents)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereClauseComponents);
        }
        return $whereClause;
    }
}