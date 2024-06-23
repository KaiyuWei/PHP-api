<?php

namespace App\Routes;

use App\Controllers\ProductController;
use App\Controllers\StockController;
use App\Controllers\SupermarketController;
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
        $router->put('/api/supermarket', [SupermarketController::class, 'updateSupermarket']);
        $router->delete('/api/supermarket/{id}', [SupermarketController::class, 'deleteSupermarket']);

        $router->get('/api/stock/index', [StockController::class, 'index']);
        $router->get('/api/stock/supermarket/{id}', [StockController::class, 'getSupermarketStock']);

        $router->dispatchRequest();
    }
}