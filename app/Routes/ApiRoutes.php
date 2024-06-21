<?php

namespace App\Routes;

use App\Helpers\Router;
//use App\Controllers\UserController;

class ApiRoutes {
    public function setup() {
        $router = new Router();

//        $router->get('/users', [UserController::class, 'index']);

        $router->dispatchRequest();
    }
}