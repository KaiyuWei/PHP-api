<?php

namespace App\Routes;

use App\Controllers\ProductController;
use App\Controllers\SupermarketController;
use App\Controllers\TestController;
use App\Controllers\UserController;
use App\Helpers\Router;

class ApiRoutes {
    public function setup() {
        $router = new Router();

        $router->post('/api/user/login', [UserController::class, 'login']);
        $router->post('/api/user/register', [UserController::class, 'register']);

        $router->get('/api/product/index', [ProductController::class, 'index']);
        $router->post('/api/product', [ProductController::class, 'addProduct']);
        $router->put('/api/product', [ProductController::class, 'updateProduct']);
        $router->delete('/api/product/{id}', [ProductController::class, 'deleteProduct']);

        $router->get('/api/supermarket/index', [SupermarketController::class, 'index']);
        $router->post('/api/supermarket', [SupermarketController::class, 'addSupermarket']);

        $router->get('/api/test', [TestController::class, 'testGet']);
        $router->dispatchRequest();
    }
}