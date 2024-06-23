<?php

namespace App\QueryFilters;

class StockQueryFilter extends QueryFilter
{
    const FILTERABLE_COLUMNS = ['product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
    public function __construct()
    {
        parent::__construct();
    }

    protected function initiateFilterableColumns(): void
    {
        $this->filterableColumns = self::FILTERABLE_COLUMNS;
    }
}