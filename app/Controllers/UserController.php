<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\UserService;
use App\Validators\UserValidator;

class UserController extends Controller {

    protected $userService;

    protected $validator;

    public function __construct() {
        parent::__construct();
        $this->userService = new UserService();
        $this->validator = new UserValidator();
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
     *     @OA\Response(response="201", description="A user is uccessfully created"),
     *     @OA\Response(response="422", description="Validation failure"),
     * )
     */
    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->validate('validateForRegisterRequest', $data);

        $this->userService->register($data);
        ResponseHelper::sendJsonResponse(['message' => 'User created successfully'], 201);
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
     *     @OA\Response(response="422", description="Validation failure"),
     * )
     */
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->validate('validateForLoginRequest', $data);

        $this->userService->login($data);
    }
}
