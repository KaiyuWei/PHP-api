<?php

namespace App\Controllers;

use App\Helpers\ResponseHelper;

class TestController
{
    public function testGet() {
        ResponseHelper::sendJsonResponse(['message' => 'hello world'], 201);
    }
}