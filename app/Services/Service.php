<?php

namespace App\Services;

use App\Models\Model;

abstract class Service
{
    protected Model $model;

    protected function isIdentifierDigit(string|int $identifier): bool
    {
        return gettype($identifier) === 'integer' || ctype_digit($identifier);
    }
}