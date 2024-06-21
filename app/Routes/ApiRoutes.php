<?php

namespace App\Routes;

use App\Controllers\ProductController;
use App\Controllers\TestController;
use App\Controllers\UserController;
use App\Helpers\Router;

class ApiRoutes {
    public function setup() {
        $router = new Router();

        $router->post('/api/user/login', [UserController::class, 'login']);
        $router->post('/api/user/register', [UserController::class, 'register']);

        $router->get('/api/product/index', [ProductController::class, 'index']);

        $router->get('/api/test', [TestController::class, 'testGet']);
        $router->dispatchRequest();
    }
}