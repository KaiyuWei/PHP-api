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
     *     @OA\Parameter(
     *         name="fields[]",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="string")
     *         ),
     *         style="form",
     *         explode=true,
     *         example={"id", "owner_id", "entry_time"},
     *         description="Fields to be queried, provided as an array of strings"
     *     ),
     *     @OA\Parameter(
     *          name="filters",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              additionalProperties=@OA\Schema(
     *                  oneOf={
     *                      @OA\Schema(type="string"),
     *                      @OA\Schema(type="integer"),
     *                      @OA\Schema(type="string", format="date-time"),
     *                      @OA\Schema(type="number", format="float")
     *                  }
     *              )
     *          ),
     *          example={"filters": {"product_id": 1, "owner_type": "supermarket"}},
     *          description="Filters for the query"
     *      ),
     *     @OA\Parameter(
     *          name="orderBy",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="object",
     *              additionalProperties=@OA\Schema(
     *                  type="string"
     *              )
     *          ),
     *          example={"orderBy": {"entry_time": "DESC", "owner_id": "ASC"}},
     *          description="Query results order by"
     *      ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1
     *         ),
     *         example=1,
     *         description="Number of Page"
     *     ),
     *     @OA\Parameter(
     *         name="recordPerPage",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1
     *         ),
     *         example=1,
     *         description="Number of records per page"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful query",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="product_id", type="integer"),
     *                     @OA\Property(property="owner_id", type="integer"),
     *                     @OA\Property(property="owner_type", type="string"),
     *                     @OA\Property(property="quantity", type="integer"),
     *                     @OA\Property(property="entry_time", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Authentication failure"
     *     )
     * )
     */
    public function index(): void
    {
        $this->authenticateUser();

        $data = $this->service->getAllWithOptionsAndPagination();
        file_put_contents(__DIR__ . '/../../storage/logs', json_encode($_GET) . "\n");
        ResponseHelper::sendJsonResponse(['data' => $data]);
    }
}