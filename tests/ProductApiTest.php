<?php

use App\Controllers\ProductController;
use PHPUnit\Framework\TestCase;
use App\Services\ProductService;
use App\Helpers\ResponseHelper;
use Faker\Factory as Faker;

class ProductControllerTest extends TestCase
{
    protected $productService;
    protected $controller;
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = $this->createMock(ProductService::class);
        $this->controller = new ProductController($this->productService);
        $this->faker = Faker::create();
    }

    public function testIndex()
    {
        $mockData = [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
        ];

        // Configure the mock to return the mock data
        $this->productService
            ->expects($this->once())
            ->method('getAll')
            ->willReturn($mockData);

        // Use output buffering to capture the response
        ob_start();
        ResponseHelper::sendJsonResponse(['data' => $mockData]);
        $output = ob_get_clean();

        // Execute the index method
        $this->controller->index();

        // Assert the output is as expected
        $this->expectOutputString($output);
    }
}
