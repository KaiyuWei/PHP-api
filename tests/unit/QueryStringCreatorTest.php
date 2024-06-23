<?php

namespace Tests\Unit;

use App\Helpers\QueryStringCreator;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\StockQuerySorter;
use PHPUnit\Framework\TestCase;

class QueryStringCreatorTest extends TestCase
{
    protected $filter;
    protected $sorter;
    protected $queryStringCreator;

    protected function setUp(): void
    {
        $this->filter = $this->createMock(StockQueryFilter::class);
        $this->sorter = $this->createMock(StockQuerySorter::class);

        $this->queryStringCreator = new QueryStringCreator($this->filter, $this->sorter);
    }

    public function test_create_select_clause_method()
    {
        $filter = new StockQueryFilter();
        $sorter = new StockQuerySorter();
        $creator = new QueryStringCreator($filter, $sorter);

        $tableName = 'users';
        $queryFields = [];
        $expected = 'SELECT * FROM users';
        $actual = $creator->createSelectClause($tableName, $queryFields);

        $tableName2 = 'users';
        $queryFields2 = ['name', 'age'];
        $expected2 = 'SELECT name, age FROM users';
        $actual2 = $creator->createSelectClause($tableName2, $queryFields2);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected2, $actual2);
    }

    public function test_create_limit_string_method()
    {
        $filter = new StockQueryFilter();
        $sorter = new StockQuerySorter();
        $creator = new QueryStringCreator($filter, $sorter);

        $limit = 0;
        $offset = 2;
        $expected = '';
        $actual = $creator->createLimitAndOffsetClause($limit, $offset);

        $limit2 = 1;
        $offset2 = 2;
        $expected2 = 'LIMIT 1 OFFSET 2';
        $actual2 = $creator->createLimitAndOffsetClause($limit2, $offset2);

        $limit3 = 1;
        $offset3 = 0;
        $expected3 = 'LIMIT 1';
        $actual3 = $creator->createLimitAndOffsetClause($limit3, $offset3);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
    }

    public function test_create_select_query_method()
    {
        $filter = new StockQueryFilter();
        $sorter = new StockQuerySorter();
        $creator = new QueryStringCreator($filter, $sorter);

        $tableName = 'stock';
        $queryFields = ['product_id', 'owner_type'];
        $filters = ['name' => 'John', 'product_id' => 1];
        $orderBys = ['entry_time' => 'ASC', 'id' => 'DESC'];
        $limit = 10;
        $offset = 5;

        $queryString = $creator->createSelectQuery($tableName, $queryFields, $filters, $orderBys, $limit, $offset);
        $expectedQueryString = 'SELECT product_id, owner_type FROM stock WHERE product_id = :product_id ORDER BY entry_time ASC LIMIT 10 OFFSET 5;';
        $this->assertEquals($expectedQueryString, $queryString);

        $tableName = 'stock';
        $queryFields = ['id', 'product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
        $filters = ['product_id' => 10, 'id' => 3];  // using 'id' to filter stock is not allowed
        $orderBys = ['entry_time' => 'DESC'];
        $limit = 5;
        $offset = 0;

        $queryString = $creator->createSelectQuery($tableName, $queryFields, $filters, $orderBys, $limit, $offset);
        $expectedQueryString = 'SELECT id, product_id, owner_id, owner_type, quantity, entry_time FROM stock WHERE product_id = :product_id ORDER BY entry_time DESC LIMIT 5;';
        $this->assertEquals($expectedQueryString, $queryString);
    }

    public function test_create_value_binding_array()
    {
        $this->filter->method('getFilterableColumns')
            ->willReturn(['name', 'age', 'address']);

        // Case 1: Test with specific input
        $filters = ['name' => 'Tom', 'address' => 'new york', 'salary' => 3000];
        $expected = [':name' => 'Tom', ':address' => 'new york'];
        $result = $this->queryStringCreator->createValueBindingArray($filters);
        $this->assertEquals($expected, $result);

        // Case 2: Test with empty input
        $filters = [];
        $expected = [];
        $result = $this->queryStringCreator->createValueBindingArray($filters);
        $this->assertEquals($expected, $result);
    }
}