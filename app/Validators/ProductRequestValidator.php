<?php

namespace App\Validators;

use App\Services\ProductService;

class ProductRequestValidator extends Validator
{
    const MAX_NAME_LENGTH = 50;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    public function validateForCreatingProduct(array $data): void
    {
        $isRequiredDataMissing = empty($data['name']);
        if($isRequiredDataMissing) {
            throw new \Exception('product name is required', 422);
        }

        $isLongerThanAllowed = strlen($data['name']) > self::MAX_NAME_LENGTH;
        if($isLongerThanAllowed) {
            throw new \Exception('product name is too long', 422);
        }
    }
}