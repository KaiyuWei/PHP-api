<?php

namespace App\Models;

use App\Database;
use App\QueryFilters\QueryFilter;
use App\QuerySorters\QuerySorter;
use PDO;

abstract class Model
{
    protected PDO $db;

    protected QueryFilter $filter;

    protected QuerySorter $sorter;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->initializeFilterAndSorter();
    }

    abstract protected function initializeFilterAndSorter(): void;
}