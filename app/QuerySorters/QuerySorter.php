<?php

namespace App\QuerySorters;

abstract class QuerySorter
{
    protected array $filterableColumns;

    public function __construct()
    {
    }
}