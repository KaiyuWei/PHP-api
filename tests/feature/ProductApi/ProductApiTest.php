<?php

namespace feature\ProductApi;

use App\Database;
use PHPUnit\Framework\TestCase;

class ProductApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->prepareData();
    }

    public function testIndex()
    {
        $response = $this->makeHttpRequest('http://localhost:8000/api/product/index');

        $expected = [
            'data' => [
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $response);
    }

    protected function makeHttpRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function prepareData()
    {
        $pdo = (new Database())->getConnection();;

        $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    )");

        $pdo->exec("TRUNCATE TABLE products");

        $stmt = $pdo->prepare("INSERT INTO products (name) VALUES (:name)");
        $stmt->execute(['name' => 'Product 1']);
        $stmt->execute(['name' => 'Product 2']);
    }
}