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

    public function updateProduct(array $data): bool
    {
        return $this->model->update($data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function isProductExisting(string|int $identifier): bool
    {
        $isProductId = $this->isIdentifierDigit($identifier);
        $result = $isProductId ? $this->model->getById($identifier) : $this->model->getByName($identifier);
        return !empty($result);
    }
}