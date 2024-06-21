<?php

namespace App\Validators;

use App\Services\ProductService;

class ProductRequestValidator
{
    protected ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }
}