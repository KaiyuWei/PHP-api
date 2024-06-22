<?php

use App\Controllers\ProductController;
use PHPUnit\Framework\TestCase;
use App\Services\ProductService;
use App\Helpers\ResponseHelper;

class ProductControllerTest extends TestCase
{
    protected ProductService $productService;
    protected ProductController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMockAndReflection();
    }

    public function testIndex()
    {
        $mockData = [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
        ];

        $temp = [];

        $this->productService
            ->expects($this->once())
            ->method('getAll')
            ->willReturn($mockData);

        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $expectedOutput = json_encode(['data' => $mockData]);
        $this->assertEquals($expectedOutput, $output);
        $this->assertTrue(false);
    }

    private function createMockAndReflection(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->controller = new ProductController();

        // Use reflection to set the protected productService property
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('productService');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->productService);
    }
}
