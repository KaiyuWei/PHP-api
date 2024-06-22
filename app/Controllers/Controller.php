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

    protected function validate($validator, $data): void
    {
        try{
            $this->validator->$validator($data);
        } catch (\Exception $e) {
            ResponseHelper::sendErrorJsonResponse($e->getMessage(), $e->getCode());
            exit();
        }
    }

    protected function getDataFromRequest(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function authenticateCurrentUser(): void
    {
        if (!Authenticator::isAuthenticated()) {
            ResponseHelper::sendErrorJsonResponse('No access to resource', 401);
            exit();
        }
    }

    protected function authenticateAdminUser(): void
    {
        if(!Authenticator::hasAdminRole()){
            ResponseHelper::sendErrorJsonResponse('You are not authorized', 401);
            exit();
        }
    }

    /**
     * This function parses uris of pattern '/api/text..text/123', and return the digit at the end.
     */
    protected function parseAndGetDigitsAtTheEndOfUri(): int
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $matches = [];

        if (preg_match('/^\/api\/[\w\/-]+\/(\d+)$/', $requestUri, $matches)) {
            // Extract the {id} parameter caught by regex
            $productId = $matches[1];
        } else {
            ResponseHelper::sendErrorJsonResponse('User Id not found', 404);
            exit();
        }

        return $productId;
    }
}