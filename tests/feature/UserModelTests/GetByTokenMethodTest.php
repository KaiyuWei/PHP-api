<?php

namespace Tests\UserModelTests;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Tests\NeedUserInDB;
use App\Database;

class GetByTokenMethodTest extends TestCase
{
    use NeedUserInDB;

    protected $pdo;

    protected function setUp(): void
    {
        $this->pdo = (new Database())->getConnection();
        $this->prepareDatabase();
    }

    protected function tearDown(): void
    {
        $this->pdo->exec('TRUNCATE TABLE users');
    }

    private function prepareDatabase(): void
    {
        $this->createUserTable();

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role, token) VALUES (:name, :email, :password, :role, :token)");
        $stmt->execute([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'hashedPassword',
            'role' => 'admin',
            'token' => 'testtoken123'
        ]);
    }

    public function testGetByTokenMethod()
    {
        $result = (new User())->getByToken('testtoken123', ['name', 'email']);
        $expected = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->assertEquals($expected, $result);
    }
}
