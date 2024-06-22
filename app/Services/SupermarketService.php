<?php

namespace App\Services;

use App\Models\Supermarket;

class SupermarketService extends Service
{
    public function __construct() {
        $this->model = new Supermarket();
    }

    public function getProductsWithIdAndNameFields(): array
    {
        if (!$this->model->getAll(['id', 'name'])) return [];
        return $this->model->getAll(['id', 'name']);
    }
}