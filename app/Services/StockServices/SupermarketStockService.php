<?php

namespace App\Services\StockServices;

use App\Models\Stock;
use App\Services\StockService;
use Exception;

class SupermarketStockService extends StockService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Stock();
    }

    public function purchaseFromSupermarket(array $data)
    {
        $supermarketId = $data['supermarketId'];
        $productId = $data['productId'];
        $requiredQuantity = $data['quantity'];

        $totalQuantityInSupermarket = $this->getTotalProductQuantityInSupermarket($productId, $supermarketId);
        $isStockEnough = $totalQuantityInSupermarket >= $requiredQuantity;

        if ($isStockEnough){
            $this->queryAndConsumeSupermarketStock($supermarketId, $productId, $requiredQuantity);
        }
        else {
            throw new Exception('Stock does not have required quantity of products', 405);
        }
    }

    private function queryAndConsumeSupermarketStock(int $supermarketId, int $productId, int $requiredQuantity): void
    {
        $availableStocks = $this->getStockInSuperMarketByEntryTimeAsc($supermarketId, $productId);
        $this->consumeAvailableStock($requiredQuantity, $availableStocks);
    }

    private function consumeAvailableStock(int $requiredQuantity, array $stocks)
    {
//        echo "stocks " .json_encode($stocks) . PHP_EOL;

        $index = 0;
        while ($requiredQuantity && isset($stocks[$index])) {
            $stockId = $stocks[$index]['id'];
            $availableQuantity = $stocks[$index]['quantity'];
            $requiredQuantity = $requiredQuantity - $availableQuantity;

            if ($requiredQuantity >= 0) {
                $this->model->delete($stockId);
            }
            else {
                $remainingQuantity = -$requiredQuantity;
                $this->updateStockByRemainingQuantity($stockId, $remainingQuantity);
            }

            $index++;
        }
    }

    private function updateStockByRemainingQuantity(int $stockId, int $remainingQuantity)
    {
        $data = ['id' => $stockId, 'quantity' => $remainingQuantity];
        $this->model->updateQuantityById($data);
    }

    private function getStockInSuperMarketByEntryTimeAsc(int $supermarketId, int $productId)
    {
        $queryFields = ['id', 'quantity'];
        $orderBy = [
            'column' => 'entry_time',
            'direction' => 'ASC', // we want the products with the earliest entry to be out first
        ];

        return $this->model->queryProductInSuperMarket($supermarketId, $productId, $queryFields, $orderBy);
    }

    public function getTotalProductQuantityInSupermarket(int $productId, int $supermarketId)
    {
        return $this->model->getTotalQuantityOfProduct($productId, 'supermarket', $supermarketId);
    }
}