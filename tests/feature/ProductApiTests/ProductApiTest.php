<?php

namespace feature\ProductApiTests;

use App\Database;
use PHPUnit\Framework\TestCase;
use Tests\NeedUserInDB;

class ProductApiTest extends TestCase
{
    use NeedUserInDB;

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

        if ($this->authToken) {
            $headers = [
                'Authorization: Bearer ' . $this->authToken,
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function prepareData()
    {
        $pdo = (new Database())->getConnection();

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS products (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL
        )");

        $this->prepareUserInDBAndGenerateToken('trainee');

        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $pdo->exec("TRUNCATE TABLE products");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        $stmt = $pdo->prepare("INSERT INTO products (name) VALUES (:name)");
        $stmt->execute(['name' => 'Product 1']);
        $stmt->execute(['name' => 'Product 2']);
    }
}