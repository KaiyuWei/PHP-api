<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\UserService;
use Dotenv\Dotenv;
use Faker\Factory;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$userService = new UserService();
$faker = Factory::create();

$email = $faker->email();
$password = 'password123';

$userInfo = [
    'name' => $faker->name(),
    'email' => $email,
    'password' => $password,
    'role' => 'admin',
];

$userService->createUser($userInfo);
$token = $userService->login(['email' => $email, 'password' => $password]);

echo json_encode([
    'email' => $email,
    'password' => $password,
    'token' => $token,
]);

