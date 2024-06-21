<?php

namespace App\Routes;

use App\Controllers\UserController;
use App\Helpers\Router;
//use App\Controllers\UserController;

class ApiRoutes {
    public function setup() {
        $router = new Router();

        $router->post('/login', [UserController::class, 'login']);

        $router->dispatchRequest();
    }
}