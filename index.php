<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Routes\ApiRoutes;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set up and dispatch routes
$routes = new ApiRoutes();
$routes->setup();