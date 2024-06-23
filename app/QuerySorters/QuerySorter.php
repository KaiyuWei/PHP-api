<?php

namespace App\QuerySorters;

abstract class QuerySorter
{
    protected array $sortableColumns;

    public function __construct()
    {
        $this->initiateSortableColumns();
    }

    abstract protected function initiateSortableColumns(): void;

    public function createOrderByClause(array $orderBys): string
    {
        $components = $this->createOrderByComponentsArray($orderBys);
        return $this->createOrderByClauseFromComponents($components);
    }

    protected function createOrderByComponentsArray(array $orderBys): array
    {
        $orderByComponents = [];
        foreach($orderBys as $orderBy => $direction)
        {
            if(in_array($orderBy, $this->sortableColumns))
            {
                $orderByComponents[] = sprintf('%s %s', $orderBy, $direction);
            }
        }
        return $orderByComponents;
    }

    protected function createOrderByClauseFromComponents(array $orderByComponents): string
    {
        $orderByClause = '';
        if (!empty($orderByComponents)) {
            $orderByClause = 'ORDER BY ' . implode(', ', $orderByComponents);
        }
        return $orderByClause;
    }
}