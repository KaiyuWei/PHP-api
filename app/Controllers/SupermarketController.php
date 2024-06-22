<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\SupermarketService;
use App\Validators\SupermarketRequestValidator;

class SupermarketController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->service = new SupermarketService();
        $this->validator = new SupermarketRequestValidator();
    }

    /**
     * @OA\Get(
     *     path="/api/supermarket/index",
     *     summary="List all supermarkets",
     *     tags={"Supermarket"},
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
     *                     @OA\Property(property="name", type="string"),
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
        $this->authenticateCurrentUser();

        $data = $this->service->getProductsWithIdAndNameFields();
        ResponseHelper::sendJsonResponse(['data' => $data]);
    }
}