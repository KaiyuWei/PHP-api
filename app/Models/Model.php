<?php

namespace App\Models;

abstract class Model
{
    protected function convertQueryFieldsToString($queryFields)
    {
        if (empty($queryFields)) return '*';
        return implode(', ', $queryFields);
    }
}