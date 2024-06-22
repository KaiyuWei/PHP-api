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
        $this->authenticateUser();

        $data = $this->service->getProductsWithIdAndNameFields();
        ResponseHelper::sendJsonResponse(['data' => $data]);
    }

    /**
     * @OA\Post(
     *     path="/api/supermarket",
     *     summary="Add a supermarket in database",
     *     tags={"Supermarket"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *            required=true,
     *            @OA\JsonContent(
     *                @OA\Property(property="name", type="string", example="Albert Heijn"),
     *            ),
     *        ),
     *     @OA\Response(response="201", description="A supermarket is added"),
     *     @OA\Response(response="401", description="Authentication failure"),
     *     @OA\Response(response="400", description="Input data is invalide"),
     *     @OA\Response(response="422", description="Input data is unprocessable"),
     * )
     */
    public function addSupermarket(): void
    {
        $this->authenticateAdminUser();

        $data = $this->getDataFromRequest();
        $this->validate('validateForCreatingSupermarket', $data);

        $result = $this->service->createSupermarket($data);
        if($result) ResponseHelper::sendSuccessJsonResponse('Supermarket created', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/supermarket",
     *     summary="Update a supermarket",
     *     tags={"Supermarket"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *            required=true,
     *            @OA\JsonContent(
     *                @OA\Property(property="id", type="integer", example=123),
     *                @OA\Property(property="name", type="string", example="Jumbo"),
     *            ),
     *        ),
     *     @OA\Response(response="200", description="Supermarket is updated"),
     *     @OA\Response(response="400", description="Input data is invalide"),
     *     @OA\Response(response="401", description="Authentication failure"),
     *     @OA\Response(response="404", description="Supermarket not found"),
     *     @OA\Response(response="422", description="Input data is unprocessable"),
     * )
     */
    public function updateSupermarket(): void
    {
        $this->authenticateAdminUser();

        $data = $this->getDataFromRequest();
        $this->validate('validateForUpdatingSupermarket', $data);

        $result = $this->service->updateSupermarket($data);
        if($result) ResponseHelper::sendSuccessJsonResponse('Supermarket updated');
    }
}