<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\ProductService;
use App\Validators\ProductRequestValidator;

class ProductController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->service = new ProductService();
        $this->validator = new ProductRequestValidator();
    }

    /**
     * @OA\Get(
     *     path="/api/product/index",
     *     summary="List all products",
     *     tags={"Product"},
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
     *       )
     * )
     */
    public function index(): void
    {
        $data = $this->service->getAll();
        ResponseHelper::sendJsonResponse(['data' => $data]);
    }
}