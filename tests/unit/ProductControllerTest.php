<?php

use App\Controllers\ProductController;
use PHPUnit\Framework\TestCase;
use App\Services\ProductService;

class ProductControllerTest extends TestCase
{
    protected ProductService $productService;
    protected ProductController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMockAndReflection();
    }

    public function test_index()
    {
        $mockData = [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
        ];

        $this->productService
            ->expects($this->once())
            ->method('getAll')
            ->willReturn($mockData);

        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $expectedOutput = json_encode(['data' => $mockData]);
        $this->assertEquals($expectedOutput, $output);
    }

    private function createMockAndReflection(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->controller = new ProductController();

        // Mock the controller and override the authenticateCurrentUser method
        $this->controller = $this->getMockBuilder(ProductController::class)
            ->onlyMethods(['authenticateCurrentUser'])
            ->disableOriginalConstructor()
            ->getMock();

        // Ensure authenticateCurrentUser does not call exit
        $this->controller->expects($this->once())
            ->method('authenticateCurrentUser')
            ->willReturnCallback(function() {});

        // Use reflection to set the protected productService property
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('service');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->productService);
    }
}
