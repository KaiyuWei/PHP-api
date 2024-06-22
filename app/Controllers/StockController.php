<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\StockService;
use App\Validators\StockRequestValidator;

class StockController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->service = new StockService();
        $this->validator = new StockRequestValidator();
    }

    /**
     * @OA\Get(
     *     path="/api/stock/index",
     *     summary="List all stock",
     *     tags={"Stock"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response="200",
     *          description="Successful query",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="product_id", type="integer"),
     *                     @OA\Property(property="owner_id", type="integer"),
     *                     @OA\Property(property="owner_type", type="string"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="entry_time", type="string", format="date-time"),
     *                 )
     *              )
     *          )
     *       ),
     *     @OA\Response(
     *         response="401",
     *         description="Authentication failure",
     *     ),
     * )
     */
    public function index(): void
    {
        $this->authenticateUser();

        $data = $this->service->getStockList();
        ResponseHelper::sendJsonResponse(['data' => $data]);
    }
}