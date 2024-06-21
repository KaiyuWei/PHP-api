<?php

namespace App\Routes;

use App\Controllers\TestController;
use App\Controllers\UserController;
use App\Helpers\Router;
//use App\Controllers\UserController;

class ApiRoutes {
    public function setup() {
        $router = new Router();

        $router->post('/api/login', [UserController::class, 'login']);

        // @todo: remove this test routing when publish
        $router->get('/api/test', [TestController::class, 'testGet']);
        $router->dispatchRequest();
    }
}