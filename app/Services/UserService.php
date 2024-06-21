<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\ResponseHelper;

class UserService extends Service {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="User login by email and password",
     *     tags={"Auth"},
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
    public function login($email, $password) {
        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            ResponseHelper::sendErrorJsonResponse('Invalid email or password', 401);
        }

        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->userModel->updateToken($user['id'], $token);

        ResponseHelper::sendJsonResponse(['token' => $token]);
    }
}
