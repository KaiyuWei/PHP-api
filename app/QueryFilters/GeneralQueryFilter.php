<?php

namespace App\QueryFilters;

/**
 * For time limitation, not all Models' filter classes are written. For those who does not have a filter class,
 * use this one for constructing. But we definitely have the necessity for filtering for all the tables. Thus,
 * a specific filter for a Model class should be there in real development.
 */
class GeneralQueryFilter extends QueryFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function initiateFilterableColumns(): void
    {
        $this->filterableColumns = [];
    }
}