<?php

namespace App\QuerySorters;

class StockQuerySorter extends QuerySorter
{
    const SORTABLE_COLUMNS = ['entry_time', 'quantity'];
    public function __construct()
    {
        parent::__construct();
    }

    protected function initiateSortableColumns(): void
    {
        $this->sortableColumns = self::SORTABLE_COLUMNS;
    }
}