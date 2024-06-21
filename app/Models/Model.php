<?php

namespace App\Models;

abstract class Model
{
    protected function convertQueryFieldsToString(array $queryFields): string
    {
        if (empty($queryFields)) return '*';
        return implode(', ', $queryFields);
    }
}