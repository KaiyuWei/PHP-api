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
    protected $pdo;
    protected $filter;
    protected $sorter;
    protected $stock;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);

        $this->filter = $this->createMock(StockQueryFilter::class);
        $this->sorter = $this->createMock(StockQuerySorter::class);

        $this->stock = new Stock($this->pdo, $this->filter, $this->sorter);
    }

    public function test_get_all_with_general_cases()
    {
        // TEST CASE 1: without offset
        $queryFields = ['id', 'product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
        $filters = ['owner_type' => 'supermarket'];
        $orderBys = ['entry_time' => 'DESC'];
        $limit = 10;
        $offset = 0;

        $expected = [
            ['id' => 46, 'product_id' => 1, 'owner_id' => 6, 'owner_type' => 'supermarket', 'quantity' => 550, 'entry_time' => '2024-06-25 07:00:00'],
            ['id' => 43, 'product_id' => 13, 'owner_id' => 3, 'owner_type' => 'supermarket', 'quantity' => 520, 'entry_time' => '2024-06-25 04:00:00'],
            ['id' => 40, 'product_id' => 10, 'owner_id' => 10, 'owner_type' => 'supermarket', 'quantity' => 490, 'entry_time' => '2024-06-25 01:00:00'],
            ['id' => 37, 'product_id' => 7, 'owner_id' => 7, 'owner_type' => 'supermarket', 'quantity' => 460, 'entry_time' => '2024-06-24 22:00:00'],
            ['id' => 34, 'product_id' => 4, 'owner_id' => 4, 'owner_type' => 'supermarket', 'quantity' => 430, 'entry_time' => '2024-06-24 19:00:00'],
            ['id' => 31, 'product_id' => 1, 'owner_id' => 1, 'owner_type' => 'supermarket', 'quantity' => 400, 'entry_time' => '2024-06-24 16:00:00'],
            ['id' => 28, 'product_id' => 13, 'owner_id' => 8, 'owner_type' => 'supermarket', 'quantity' => 370, 'entry_time' => '2024-06-24 13:00:00'],
            ['id' => 25, 'product_id' => 10, 'owner_id' => 5, 'owner_type' => 'supermarket', 'quantity' => 340, 'entry_time' => '2024-06-24 10:00:00'],
            ['id' => 22, 'product_id' => 7, 'owner_id' => 2, 'owner_type' => 'supermarket', 'quantity' => 310, 'entry_time' => '2024-06-24 07:00:00'],
            ['id' => 19, 'product_id' => 4, 'owner_id' => 9, 'owner_type' => 'supermarket', 'quantity' => 280, 'entry_time' => '2024-06-24 04:00:00']
        ];

        $actual = $this->stock->getAllWith($queryFields, $filters, $orderBys, $limit, $offset);
        $this->assertEquals($expected, $actual);

        // TEST CASE 2: with offset
        $limit = 5;
        $offset = 4;

        $expected = [
            ['id' => 34, 'product_id' => 4, 'owner_id' => 4, 'owner_type' => 'supermarket', 'quantity' => 430, 'entry_time' => '2024-06-24 19:00:00'],
            ['id' => 31, 'product_id' => 1, 'owner_id' => 1, 'owner_type' => 'supermarket', 'quantity' => 400, 'entry_time' => '2024-06-24 16:00:00'],
            ['id' => 28, 'product_id' => 13, 'owner_id' => 8, 'owner_type' => 'supermarket', 'quantity' => 370, 'entry_time' => '2024-06-24 13:00:00'],
            ['id' => 25, 'product_id' => 10, 'owner_id' => 5, 'owner_type' => 'supermarket', 'quantity' => 340, 'entry_time' => '2024-06-24 10:00:00'],
            ['id' => 22, 'product_id' => 7, 'owner_id' => 2, 'owner_type' => 'supermarket', 'quantity' => 310, 'entry_time' => '2024-06-24 07:00:00']
        ];

        $actual = $this->stock->getAllWith($queryFields, $filters, $orderBys, $limit, $offset);
        $this->assertEquals($expected, $actual);
    }

    public function test_get_all_with_not_allowed_filtering_field()
    {
        $queryFields = ['id', 'product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
        $filters = ['product_id' => 10, 'id' => 3];  // using 'id' to filter stock is not allowed
        $orderBys = ['entry_time' => 'DESC'];
        $limit = 5;
        $offset = 0;

        $expected = [
            ['id' => 40, 'product_id' => 10, 'owner_id' => 10, 'owner_type' => 'supermarket', 'quantity' => 490, 'entry_time' => '2024-06-25 01:00:00'],
            ['id' => 25, 'product_id' => 10, 'owner_id' => 5, 'owner_type' => 'supermarket', 'quantity' => 340, 'entry_time' => '2024-06-24 10:00:00'],
            ['id' => 10, 'product_id' => 10, 'owner_id' => 10, 'owner_type' => 'supermarket', 'quantity' => 190, 'entry_time' => '2024-06-23 19:00:00']
        ];

        $actual = $this->stock->getAllWith($queryFields, $filters, $orderBys, $limit, $offset);
        $this->assertEquals($expected, $actual);
    }

    public function test_get_all_with_offset_without_limit()
    {
        $queryFields = ['id', 'product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'];
        $filters = ['product_id' => 1, 'id' => 3];
        $orderBys = ['entry_time' => 'DESC'];
        $limit = 0;
        $offset = 5;  // no limit, so offset should not be applied in the query.

        $expected = [
            ['id' => 46, 'product_id' => 1, 'owner_id' => 6, 'owner_type' => 'supermarket', 'quantity' => 550, 'entry_time' => '2024-06-25 07:00:00'],
            ['id' => 31, 'product_id' => 1, 'owner_id' => 1, 'owner_type' => 'supermarket', 'quantity' => 400, 'entry_time' => '2024-06-24 16:00:00'],
            ['id' => 16, 'product_id' => 1, 'owner_id' => 6, 'owner_type' => 'supermarket', 'quantity' => 250, 'entry_time' => '2024-06-24 01:00:00'],
            ['id' => 1, 'product_id' => 1, 'owner_id' => 1, 'owner_type' => 'supermarket', 'quantity' => 100, 'entry_time' => '2024-06-23 10:00:00']
        ];

        $actual = $this->stock->getAllWith($queryFields, $filters, $orderBys, $limit, $offset);
        $this->assertEquals($expected, $actual);
    }

    public function test_get_all_with_does_not_return_not_allowed_query_fields()
    {
        $queryFields = ['id', 'product_id', 'entry_time', 'created_at'];  // 'created_at' is not a allowed query field
        $filters = ['product_id' => 1, 'id' => 3];  // id is not an allowed filter fields
        $orderBys = ['entry_time' => 'DESC', 'product_id' => 'DESC', 'quantity' => 'ASC']; // 'product_id' is not a sortable field
        $limit = 3;
        $offset = 2;

        $expected = [
            ['id' => 16, 'product_id' => 1, 'entry_time' => '2024-06-24 01:00:00'],
            ['id' => 1, 'product_id' => 1, 'entry_time' => '2024-06-23 10:00:00']
        ];

        $actual = $this->stock->getAllWith($queryFields, $filters, $orderBys, $limit, $offset);
        $this->assertEquals($expected, $actual);
    }
}

?>
