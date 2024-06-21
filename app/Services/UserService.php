<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ResponseHelper;

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($data) {
        list('email' => $email, 'password' => $password) = $data;
        $user = $this->userModel->getUserByEmail($email, ['id', 'password']);

        $this->isUserExisting($user);
        $this->checkPassword($password, $user['password']);

        $token = $this->generateAndUpdateTokenForUser($user['id']);
        ResponseHelper::sendJsonResponse(['token' => $token]);
    }

    public function register($data)
    {
        // only the role 'trainee' can be registered from the api.
        $data['role'] = 'trainee';
        $this->userModel->createUser($data);
    }

    public function isEmailRegistered($email)
    {
        $result = $this->userModel->getUserByEmail($email, ['id']);
        return !empty($result);
    }

    private function isUserExisting($user) {
        if (!$user) {
            ResponseHelper::sendErrorJsonResponse('Email does not exist', 401);
        }
    }

    private function checkPassword($inputPassword, $realPasswordHash)
    {
        $isPasswordCorrect = password_verify($inputPassword, $realPasswordHash);
        if (!$isPasswordCorrect) {
            ResponseHelper::sendErrorJsonResponse('Invalid password', 401);
        }
    }

    private function generateAndUpdateTokenForUser($userId) {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->userModel->updateToken($userId, $token);

        return $token;
    }
}
