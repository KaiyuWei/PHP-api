<?php

namespace App\Validators;

use App\Services\UserService;

class UserRequestValidator
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function validateForRegisterRequest($data)
    {
        $isRequiredDataMissing = empty($data['name']) || empty($data['email']) || empty($data['password']);
        if($isRequiredDataMissing) {
            throw new \Exception('Name, email and password are required', 422);
        }

        $isEmailRegistered = $this->userService->isEmailRegistered($data['email']);
        if($isEmailRegistered) {
            throw new \Exception('This email is already registered', 422);
        }
    }

    public function validateForLoginRequest($data)
    {
        $isRequiredDataMissing = empty($data['email']) || empty($data['password']);
        if($isRequiredDataMissing) {
            throw new \Exception('Email and password are required', 422);
        }
    }
}