<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends Service
{
    public function __construct() {
        $this->model = new Product();
    }

    public function getProductsWithIdAndNameFields(): array
    {
        if (!$this->model->getAll(['id', 'name'])) return [];
        return $this->model->getAll(['id', 'name']);
    }

    public function createProduct(array $data): bool
    {
        return $this->model->create($data);
    }
}