<?php

namespace App\Validators;

use App\Services\UserService;

class UserRequestValidator extends Validator
{
    const MAX_NAME_LENGTH = 50;

    const MAX_EMAIL_LENGTH = 50;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function validateForRegisterRequest(array $data): void
    {
        $isRequiredDataMissing = empty($data['name']) || empty($data['email']) || empty($data['password']);
        if($isRequiredDataMissing) {
            throw new \Exception('Name, email and password are required', 400);
        }

        $isNameLongerThanAllowed = strlen($data['name']) > self::MAX_NAME_LENGTH;
        if($isNameLongerThanAllowed) {
            throw new \Exception('User name is too long', 422);
        }

        $isEmailLongerThanAllowed = strlen($data['name']) > self::MAX_EMAIL_LENGTH;
        if($isEmailLongerThanAllowed) {
            throw new \Exception('Email is too long', 422);
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
            throw new \Exception('Email and password are required', 400);
        }
    }
}