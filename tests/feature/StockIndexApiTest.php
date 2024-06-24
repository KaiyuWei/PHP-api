<?php

use App\Database;
use PHPUnit\Framework\TestCase;
use Tests\NeedUserInDB;

class StockIndexApiTest extends TestCase
{
    use NeedUserInDB;

    private $baseUrl;

    private $pdo;

    protected function setUp(): void
    {
        $this->baseUrl = 'http://localhost:8000/api/stock/index';
        $this->pdo = (new Database())->getConnection();

        $this->prepareUserInDBAndGenerateToken('admin');
        $this->runSeeder();
    }

    public function test_successful_query()
    {
        $url = $this->baseUrl . '?fields%5B%5D=id&fields%5B%5D=product_id&fields%5B%5D=owner_id&fields%5B%5D=quantity&fields%5B%5D=owner_type&fields%5B%5D=entry_time&filters[owner_type]=supermarket&orderBy[entry_time]=DESC&orderBy[owner_id]=ASC';
        $response = $this->makeHttpRequest('GET', $url);

        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('data', $response['body']);
        $this->assertIsArray($response['body']['data']);
    }

    public function test_authentication_failure()
    {
        $url = $this->baseUrl;
        $response = $this->makeHttpRequest('GET', $url, null, false);

        $this->assertEquals(401, $response['status']);
        $this->assertEquals(false, $response['body']['success']);
    }

    public function test_authentication_fai()
    {
        $url = $this->baseUrl;
        $response = $this->makeHttpRequest('GET', $url, null, false);

        $this->assertEquals(401, $response['status']);
        $this->assertEquals(false, $response['body']['success']);
    }

    private function makeHttpRequest($method, $url, $data = null, $authenticated = true)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        } elseif ($method === 'GET' && $data) {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
        }

        if ($authenticated) {
            $token = $this->authToken;
            $headers = [
                "Authorization: Bearer $token"
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $httpCode,
            'body' => json_decode($response, true)
        ];
    }

    protected function runSeeder()
    {
        $sqlFilePath = __DIR__ . '/../../database/seeders/insert_into_products_and_stock_table.sql';
        $sql = file_get_contents($sqlFilePath);

        $statements = explode(';', $sql);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $this->pdo->exec($statement);
            }
        }
    }
}
