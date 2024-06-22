<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\Supermarket;

class SupermarketService extends Service
{
    public function __construct() {
        $this->model = new Supermarket();
    }

    public function getProductsWithIdAndNameFields(): array
    {
        $result = $this->model->getAll(['id', 'name']);
        return $result ?? [];
    }

    public function isSupermarketExisting(string|int $identifier): bool
    {
        $isSupermarketId = $this->isIdentifierDigit($identifier);
        $result = $isSupermarketId ? $this->model->getById($identifier) : $this->model->getByName($identifier);
        return !empty($result);
    }

    public function createSupermarket(array $data): bool
    {
        return $this->model->create($data);
    }

    public function updateSupermarket(array $data): bool
    {
        return $this->model->update($data);
    }

    public function deleteSupermarketAndItsStock(int $id): bool
    {
        return $this->model->deleteWithOwnedStock($id);
    }
}