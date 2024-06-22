<?php

namespace App\Validators;

use App\Services\UserService;

class UserRequestValidator extends Validator
{
    public function __construct()
    {
        $this->service = new UserService();
    }

    public function validateForRegisterRequest(array $data): void
    {
        $isRequiredDataMissing = empty($data['name']) || empty($data['email']) || empty($data['password']);
        if($isRequiredDataMissing) {
            throw new \Exception('Name, email and password are required', 422);
        }

        $isEmailRegistered = $this->service->isEmailRegistered($data['email']);
        if($isEmailRegistered) {
            throw new \Exception('This email is already registered', 422);
        }
    }

    public function validateForLoginRequest(array $data): void
    {
        $isRequiredDataMissing = empty($data['email']) || empty($data['password']);
        if($isRequiredDataMissing) {
            throw new \Exception('Email and password are required', 422);
        }
    }
}