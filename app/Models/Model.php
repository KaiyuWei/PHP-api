<?php

namespace App\Models;

use PDO;

abstract class Model
{
    protected PDO $db;
    
    protected function convertQueryFieldsToString(array $queryFields): string
    {
        return empty($queryFields) ? '*' : implode(', ', $queryFields);
    }
}