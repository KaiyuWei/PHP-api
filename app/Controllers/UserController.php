<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\UserService;
use App\Validators\UserRequestValidator;
use Dotenv\Validator;

class UserController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->service = new UserService();
        $this->validator = new UserRequestValidator();
    }

    /**
     * @OA\Post(
     *     path="/api/user/register",
     *     summary="Create a user in the database",
     *     tags={"User Auth"},
     *     @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="David"),
     *               @OA\Property(property="email", type="string", example="user@example.com"),
     *               @OA\Property(property="password", type="string", example="secretpassword"),
     *           )
     *       ),
     *     @OA\Response(response="201", description="A user is uccessfully registered"),
     *     @OA\Response(response="400", description="Input data is invalide"),
     *     @OA\Response(response="422", description="Input data is unprocessable"),
     * )
     */
    public function register(): void
    {
        $data = $this->getDataFromRequest();
        $this->validate('validateForRegisterRequest', $data);

        $result = $this->service->register($data);
        if($result) ResponseHelper::sendJsonResponse(['message' => 'User created successfully'], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/user/login",
     *     summary="User login by email and password",
     *     tags={"User Auth"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", example="user@example.com"),
     *              @OA\Property(property="password", type="string", example="secretpassword"),
     *          )
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="Successful login",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string")
     *          )
     *      ),
     *     @OA\Response(response="401", description="Invalid email or password"),
     *     @OA\Response(response="400", description="Input data is invalide"),
     *     @OA\Response(response="422", description="Input data is unprocessable"),
     * )
     */
    public function login(): void
    {
        $data = $this->getDataFromRequest();
        $this->validate('validateForLoginRequest', $data);

        $token = $this->service->login($data);
        ResponseHelper::sendJsonResponse(['token' => $token]);
    }
}
