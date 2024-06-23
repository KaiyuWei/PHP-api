<?php

namespace Tests\Feature;

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

    public function testCreateSelectQuery()
    {
        $this->filter->method('createWhereClause')
            ->willReturn('WHERE name = :name AND id = :id');

        $this->sorter->method('createOrderByClause')
            ->willReturn('ORDER BY name ASC, id DESC');

        $tableName = 'users';
        $queryFields = ['id', 'name', 'email'];
        $filters = ['name' => 'John', 'id' => 1];
        $orderBys = ['name' => 'ASC', 'id' => 'DESC'];
        $limit = 10;
        $offset = 5;

        $queryString = $this->queryStringCreator->createSelectQuery($tableName, $queryFields, $filters, $orderBys, $limit, $offset);
        $expectedQueryString = 'SELECT id, name, email FROM users WHERE name = :name AND id = :id ORDER BY name ASC, id DESC LIMIT 10 OFFSET 5;';

        $this->assertEquals($expectedQueryString, $queryString);
    }
}