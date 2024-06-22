<?php

namespace App\Controllers;

use App\Helpers\Authenticator;
use App\Helpers\ResponseHelper;
use App\Services\Service;
use App\Validators\Validator;

/**
 * @OA\OpenApi(
 *      @OA\Info(title="Roamler API", version="1.0"),
 *      @OA\Components(
 *          @OA\SecurityScheme(
 *           securityScheme="bearerAuth",
 *           type="http",
 *           scheme="bearer",
 *           bearerFormat="JWT",
 *         ),
 *      ),
 * )
 */
abstract class Controller
{
    protected Service $service;

    protected Validator $validator;

    public function __construct()
    {
        //
    }

    protected function validate($validator, $data)
    {
        try{
            $this->validator->$validator($data);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorJsonResponse($e->getMessage(), $e->getCode());
            exit();
        }
    }

    protected function authenticateCurrentUser(): void
    {
        if (!Authenticator::isAuthenticated()) {
            ResponseHelper::sendErrorJsonResponse('No access to resource', 401);
            exit();
        }
    }
}