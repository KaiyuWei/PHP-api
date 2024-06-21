<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends Service
{
    protected $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function getAll() {
        if (!$this->productModel->getAll(['id', 'name'])) return [];
        return $this->productModel->getAll(['id', 'name']);
    }
}