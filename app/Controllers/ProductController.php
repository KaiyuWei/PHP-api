<?php

namespace App\Controllers;

use App\Helpers\Authenticator;
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

    /**
     * @OA\Post(
     *     path="/api/product",
     *     summary="Add a product in database",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *            required=true,
     *            @OA\JsonContent(
     *                @OA\Property(property="name", type="string", example="IPhone 100"),
     *            ),
     *        ),
     *     @OA\Response(response="201", description="A product is uccessfully added"),
     *     @OA\Response(response="401", description="Authentication failure"),
     *     @OA\Response(response="422", description="Validation failure"),
     * )
     */
    public function addProduct(): void
    {
        $this->authenticateAdminUser();

        $data = $this->getDataFromRequest();
        $this->validate('validateForCreatingProduct', $data);

        $result = $this->service->createProduct($data);
        if($result) ResponseHelper::sendSuccessJsonResponse('Product created', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/product",
     *     summary="Update a product in database",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *            required=true,
     *            @OA\JsonContent(
     *                @OA\Property(property="id", type="int", example=123),
     *                @OA\Property(property="name", type="string", example="IPhone 100"),
     *            ),
     *        ),
     *     @OA\Response(response="200", description="Product is uccessfully updated"),
     *     @OA\Response(response="401", description="Authentication failure"),
     *     @OA\Response(response="422", description="Validation failure"),
     *     @OA\Response(response="404", description="Product not found"),
     * )
     */
    public function updateProduct(): void
    {
        $this->authenticateAdminUser();

        $data = $this->getDataFromRequest();
        $this->validate('validateForUpdatingProduct', $data);

        $result = $this->service->updateProduct($data);
        if($result) ResponseHelper::sendSuccessJsonResponse('Product updated');
    }
}