<?php

namespace App\QueryFilters;

abstract class QueryFilter
{
    protected array $filterableColumns;

    public function __construct()
    {
    }
}