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

        // mock the result of the function call "getAll()"
        $this->productService->expects($this->once())->method('getAll')->willReturn($mockData);

        $output = $this->getIndexResultInOutputBuffer();

        $expectedOutput = json_encode(['data' => $mockData]);
        $this->assertEquals($expectedOutput, $output);
    }

    private function getIndexResultInOutputBuffer()
    {
        ob_start();
        $this->controller->index();
        return ob_get_clean();
    }

    private function createMockAndReflection(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->controller = new ProductController();
        $this->mockAuthenticator();
        $this->createReflectionForServicePropertyInController();
    }

    private function mockAuthenticator()
    {
        $this->controller = $this->getMockBuilder(ProductController::class)
            ->onlyMethods(['authenticateUser'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller->expects($this->once())
            ->method('authenticateUser')
            ->willReturnCallback(function() {});
    }

    private function createReflectionForServicePropertyInController()
    {
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('service');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->productService);
    }
}
