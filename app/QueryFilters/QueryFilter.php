<?php

namespace App\QueryFilters;

abstract class QueryFilter
{
    protected array $filterableColumns;

    public function __construct()
    {
        $this->initiateFilterableColumns();
    }

    abstract protected function initiateFilterableColumns(): void;

    public function createWhereClause(array $filters): string
    {
        $whereClauseComponents = $this->createWhereClauseComponents($filters);
        return $this->createWhereClauseFromComponents($whereClauseComponents);
    }

    protected function createWhereClauseComponents(array $filters): array
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

    protected function createWhereClauseFromComponents(array $whereClauseComponents): string
    {
        $whereClause = '';
        if (!empty($whereClauseComponents)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereClauseComponents);
        }
        return $whereClause;
    }
}