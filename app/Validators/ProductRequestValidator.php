<?php

namespace App\Validators;

use App\Services\ProductService;

class ProductRequestValidator extends Validator
{
    public function __construct()
    {
        $this->service = new ProductService();
    }
}