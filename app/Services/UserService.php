<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ResponseHelper;

class UserService extends Service {

    public function __construct() {
        $this->model = new User();
    }

    public function login(array $data): string
    {
        list('email' => $email, 'password' => $password) = $data;
        $user = $this->model->getByEmail($email, ['id', 'password']);

        $this->checkUserExistence($user);
        $this->checkPassword($password, $user['password']);

        return $this->generateAndUpdateTokenForUser($user['id']);
    }

    public function register(array $data): bool
    {
        // only the role 'trainee' can be registered from the api.
        $data['role'] = 'trainee';
        return $this->model->create($data);
    }

    public function isEmailRegistered(string $email): bool
    {
        $result = $this->model->getByEmail($email, ['id']);
        return !empty($result);
    }

    public function getUser(string|int $identifier): array|false
    {
        $isUserId = $this->isIdentifierDigit($identifier);
        $user = $isUserId ? $this->model->getById($identifier) : $this->model->getByToken($identifier);

        return $user;
    }

    public function getRole(string|int $identifier): string|false
    {
        $user = $this->getUser($identifier);
        return $user ? $user['role'] : false;
    }

    public function createUser(array $data): string
    {
        return $this->model->create($data);
    }

    private function checkUserExistence($user):void
    {
        if (!$user) {
            ResponseHelper::sendErrorJsonResponse('Email does not exist', 401);
            exit();
        }
    }

    private function checkPassword(string $inputPassword, string $realPasswordHash): void
    {
        $isPasswordCorrect = password_verify($inputPassword, $realPasswordHash);
        if (!$isPasswordCorrect) {
            ResponseHelper::sendErrorJsonResponse('Invalid password', 401);
            exit();
        }
    }

    private function generateAndUpdateTokenForUser(int $userId): string
    {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->model->updateToken($userId, $token);

        return $token;
    }
}
