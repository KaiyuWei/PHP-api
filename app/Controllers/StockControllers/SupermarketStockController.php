<?php

namespace App\Controllers\StockControllers;

use App\Controllers\StockController;
use App\Helpers\ResponseHelper;
use App\Services\StockService;
use App\Services\StockServices\SupermarketStockService;
use App\Validators\StockRequestValidator;
use Exception;

class SupermarketStockController extends StockController
{
    public function __construct() {
        parent::__construct();
        $this->service = new SupermarketStockService();
        $this->validator = new StockRequestValidator();
    }

    /**
     * @OA\Post(
     *     path="/api/purchase/supermarket",
     *     summary="Purchase something from a supermarket",
     *     tags={"Order Process"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *            required=true,
     *            @OA\JsonContent(
     *                @OA\Property(property="supermarketId", type="integer", example=10),
     *                @OA\Property(property="productId", type="integer", example=2),
     *                @OA\Property(property="quantity", type="integer", example=50),
     *            ),
     *        ),
     *     @OA\Response(response="200", description="Purchase completed"),
     *     @OA\Response(response="400", description="Input data is invalid"),
     *     @OA\Response(response="401", description="Authentication failure"),
     *     @OA\Response(response="404", description="Supermarket or product not found"),
     *     @OA\Response(response="405", description="Stock not enough"),
     * )
     */
    public function purchaseFromSupermarket(): void
    {
        $this->authenticateUser();

        $data = $this->getDataFromRequest();
        $this->validate('validateForPurchaseInSupermarketRequest', $data);

        try{
            $this->service->purchaseFromSupermarket($data);
            ResponseHelper::sendSuccessJsonResponse('Products has been sent out from the inventory!');
        } catch (Exception $e) {
            ResponseHelper::sendErrorJsonResponse($e->getMessage(), $e->getCode());
        }
    }
}