<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\StockQuerySorter;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use App\Helpers\QueryStringCreator;

class StockModelTest extends TestCase
{
    protected $stock;

    protected function setUp(): void
    {
        $this->stock = new Stock();
    }
}
