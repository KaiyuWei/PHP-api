<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\Service;
use App\Validators\Validator;

/**
 * @OA\Info(title="Roamler API", version="1.0")
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
}