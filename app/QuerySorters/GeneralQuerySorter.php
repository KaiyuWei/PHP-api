<?php

namespace App\QuerySorters;

/**
 * For time limitation, not all Models' sorter classes are written. For those who does not have a sorter class,
 * use this one for constructing. But we definitely have the necessity for sorting for all the tables. Thus,
 * a specific sorter for a Model class should be there in real development.
 */
class GeneralQuerySorter extends QuerySorter
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function initiateSortableColumns(): void
    {
        $this->sortableColumns = [];
    }
}