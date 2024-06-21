<?php

namespace App\Validators;

use App\Services\ProductService;

class ProductRequestValidator
{
    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }
}