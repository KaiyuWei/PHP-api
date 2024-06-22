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

        if($this->service->isProductExisting($data['name'])) {
            throw new \Exception('product is existing', 422);
        }
    }

    public function validateForUpdatingProduct(array $data): void
    {
        $isRequiredDataMissing = empty($data['id']) || empty($data['name']);
        if($isRequiredDataMissing) {
            throw new \Exception('product id, name are required', 422);
        }

        $isLongerThanAllowed = strlen($data['name']) > self::MAX_NAME_LENGTH;
        if($isLongerThanAllowed) {
            throw new \Exception('product name is too long', 422);
        }

        $isProductFound = $this->service->isProductExisting($data['id']);
        if(!$isProductFound) {
            throw new \Exception('product not found', 404);
        }
    }
}