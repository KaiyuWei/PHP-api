<?php

namespace App\Models;

use App\QueryFilters\GeneralQueryFilter;
use App\QuerySorters\GeneralQuerySorter;

class Supermarket extends Model
{
    const QUERIABLE_FIELDS = ['id', 'name'];

    public function __construct() {
        parent::__construct();
    }

    protected function initializeFilterAndSorter(): void
    {
        $this->filter = new GeneralQueryFilter();
        $this->sorter = new GeneralQuerySorter();
    }

    protected function initializeQueriableFields(): void
    {
        $this->queriableFields = self::QUERIABLE_FIELDS;
    }
}