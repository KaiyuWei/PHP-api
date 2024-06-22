<?php

namespace Tests\Unit;

use App\QuerySorters\StockQuerySorter;
use PHPUnit\Framework\TestCase;

class StockQuerySorterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_create_sort_by_clause_method()
    {
        $sorter = new StockQuerySorter();

        $sorters1 = [];
        $expected1 = '';
        $actual1 = $sorter->createOrderByClause($sorters1);

        $sorters2 = ['entry_time' => 'DESC', 'fake_column' => 'ASC', 'quantity' => 'ASC'];
        $expected2 = 'ORDER BY entry_time DESC, quantity ASC';
        $actual2 = $sorter->createOrderByClause($sorters2);

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }
}