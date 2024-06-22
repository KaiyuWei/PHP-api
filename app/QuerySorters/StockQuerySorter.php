<?php

namespace App\QuerySorters;

class StockQuerySorter extends QuerySorter
{
    const FILTERABLE_COLUMNS = ['entry_time', 'quantity'];
    public function __construct()
    {
        parent::__construct();
        $this->filterableColumns = self::FILTERABLE_COLUMNS;
    }

    public function createOrderByClause(array $orderBys): string
    {
        $components = $this->createOrderByComponentsArray($orderBys);
        return $this->createOrderByClauseFromComponents($components);
    }

    private function createOrderByComponentsArray(array $orderBys): array
    {
        $orderByComponents = [];
        foreach($orderBys as $orderBy => $direction)
        {
            if(in_array($orderBy, $this->filterableColumns))
            {
                $orderByComponents[] = sprintf('%s %s', $orderBy, $direction);
            }
        }
        return $orderByComponents;
    }

    private function createOrderByClauseFromComponents(array $orderByComponents): string
    {
        $orderByClause = '';
        if (!empty($orderByComponents)) {
            $orderByClause = 'ORDER BY ' . implode(', ', $orderByComponents);
        }
        return $orderByClause;
    }
}