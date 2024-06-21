<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ResponseHelper;

class UserService extends Service {
    protected User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(array $data): string
    {
        list('email' => $email, 'password' => $password) = $data;
        $user = $this->userModel->getByEmail($email, ['id', 'password']);

        $this->checkUserExistence($user);
        $this->checkPassword($password, $user['password']);

        return $this->generateAndUpdateTokenForUser($user['id']);
    }

    public function register(array $data): bool
    {
        // only the role 'trainee' can be registered from the api.
        $data['role'] = 'trainee';
        return $this->userModel->create($data);
    }

    public function isEmailRegistered(string $email): bool
    {
        $result = $this->userModel->getByEmail($email, ['id']);
        return !empty($result);
    }

    private function checkUserExistence($user):void
    {
        if (!$user) {
            ResponseHelper::sendErrorJsonResponse('Email does not exist', 401);
        }
    }

    private function checkPassword(string $inputPassword, string $realPasswordHash): void
    {
        $isPasswordCorrect = password_verify($inputPassword, $realPasswordHash);
        if (!$isPasswordCorrect) {
            ResponseHelper::sendErrorJsonResponse('Invalid password', 401);
        }
    }

    private function generateAndUpdateTokenForUser(int $userId): string
    {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->userModel->updateToken($userId, $token);

        return $token;
    }
}
