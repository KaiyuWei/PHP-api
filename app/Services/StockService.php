<?php

namespace App\Services;

use App\Models\Stock;

class StockService extends Service
{
    public function __construct() {
        $this->model = new Stock();
    }

    public function getStockList(): array
    {
        $result = $this->model->getAll([
            'product_id', 'owner_id', 'owner_type', 'quantity', 'entry_time'
        ]);
        return $result ?? [];
    }
}