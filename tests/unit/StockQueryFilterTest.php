<?php

namespace Tests\Unit;

use App\QueryFilters\StockQueryFilter;
use PHPUnit\Framework\TestCase;

class StockQueryFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_build_where_clause_method()
    {
        $stockFilter = new StockQueryFilter();

        $filters1 = [];
        $expected1 = '';
        $actual1 = $stockFilter->buildWhereClause($filters1);

        $filters2 = [
            'product_id' => 12,
            'owner_type' => 'supermarket',
            'non_existing_column' => 'blablabla',
            'quantity' => 3];
        $expected2 = 'WHERE product_id = :product_id AND owner_type = :owner_type AND quantity = :quantity';
        $actual2 = $stockFilter->buildWhereClause($filters2);

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }
}