<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends Service
{
    protected Product $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function getAll(): array
    {
        if (!$this->productModel->getAll(['id', 'name'])) return [];
        return $this->productModel->getAll(['id', 'name']);
    }
}