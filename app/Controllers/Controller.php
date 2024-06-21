<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;

/**
 * @OA\Info(title="Roamler API", version="1.0")
 */
abstract class Controller
{
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
        }
    }
}