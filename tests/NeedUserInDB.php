<?php

namespace Tests;

use App\Database;
use App\Models\User;
use App\Services\UserService;

trait NeedUserInDB
{
    protected $authToken;

    public function truncateUsersTable()
    {
        (new Database())->getConnection()->exec('TRUNCATE TABLE users');
    }

    public function createUserTable()
    {
        (new Database())->getConnection()->exec("
            CREATE TABLE IF NOT EXISTS users (
                `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(50) NOT NULL,
                `email` VARCHAR(50) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('admin', 'trainee') NOT NULL DEFAULT 'trainee',
                `token` VARCHAR(255) DEFAULT NULL
            )
        ");
    }

    public function prepareAdminUser()
    {
        $this->truncateUsersTable();
        $user = new User();

        $data = [
            'name' => 'Mike',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ];

        $user->create($data);
    }

    public function prepareTraineeUser()
    {
        $this->truncateUsersTable();
        $user = new User();

        $data = [
            'name' => 'Mike',
            'email' => 'trainee@example.com',
            'password' => 'password123',
            'role' => 'trainee',
        ];

        $user->create($data);
    }

    public function getBearerToken(string $email, string $password): string
    {
        $userService = new UserService();

        return $userService->login(
            [
                'email' => $email,
                'password' => $password,
            ]);
    }

    public function prepareUserInDBAndGenerateToken(string $role)
    {
        $this->createUserTable();
        $this->truncateUsersTable();

        $isAdmin = $role === 'admin';
        $isAdmin ? $this->prepareAdminUser() : $this->prepareTraineeUser();
        $email = $isAdmin ? 'admin@example.com' : 'trainee@example.com';
        $password = 'password123';

        $this->authToken = $this->getBearerToken($email, $password);
    }
}