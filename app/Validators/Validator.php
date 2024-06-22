<?php

namespace App\Validators;

use App\Services\Service;

abstract class Validator
{
    const MAX_NAME_LENGTH = 50;
    protected Service $service;

    public function checkIfInputNull($data)
    {
        if (!$data) {
            throw new \Exception('Invalid input', 400);
        }
    }
}