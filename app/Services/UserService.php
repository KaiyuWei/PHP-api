<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ResponseHelper;

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($email, $password) {
        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            ResponseHelper::sendErrorJsonResponse('Invalid email or password', 401);
        }

        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->userModel->updateToken($user['id'], $token);

        ResponseHelper::sendJsonResponse(['token' => $token]);
    }
}
