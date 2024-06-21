<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Services\UserService;

class UserController extends Controller {
    private $user;

    private $userService;

    public function __construct() {
        $this->userService = new UserService();
        $this->user = new User();
    }

    /**
     * @OA\Post(
     *     path="/api/user/register",
     *     summary="Create a user in the database",
     *     tags={"User"},
     *     @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               @OA\Property(property="name", type="string", example="David"),
     *               @OA\Property(property="email", type="string", example="user@example.com"),
     *               @OA\Property(property="password", type="string", example="secretpassword"),
     *           )
     *       ),
     *     @OA\Response(response="201", description="A trainee is uccessfully created"),
     *     @OA\Response(response="422", description="Validation failure"),
     * )
     */
    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            ResponseHelper::sendErrorJsonResponse('Name, email and password are required', 422);
        }

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
     *     @OA\Response(response="404", description="Email not found"),
     *     @OA\Response(response="401", description="Password is wrong"),
     *     @OA\Response(response="422", description="Validation failure"),
     * )
     */
    public function login() {
        $data = $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            ResponseHelper::sendErrorJsonResponse('Email and password are required', 400);
        }

        $this->userService->login($data);
    }
}
