<?php

namespace App\Models;

use PDO;

abstract class Model
{
    protected PDO $db;
    
    protected function convertQueryFieldsToString(array $queryFields): string
    {
        if (empty($queryFields)) return '*';
        return implode(', ', $queryFields);
    }
}