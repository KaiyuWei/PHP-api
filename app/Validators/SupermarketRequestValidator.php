<?php

namespace App\Validators;

use App\Services\SupermarketService;

class SupermarketRequestValidator extends Validator
{
    public function __construct()
    {
        $this->service = new SupermarketService();
    }

    public function validateForCreatingSupermarket(array $data): void
    {
        $isRequiredDataMissing = empty($data['name']);
        if($isRequiredDataMissing) {
            throw new \Exception('Supermarket name is required', 422);
        }

        $isLongerThanAllowed = strlen($data['name']) > static::MAX_NAME_LENGTH;
        if($isLongerThanAllowed) {
            throw new \Exception('Supermarket name is too long', 422);
        }

        if($this->service->isSupermarketExisting($data['name'])) {
            throw new \Exception('Supermarket is existing', 422);
        }
    }
}