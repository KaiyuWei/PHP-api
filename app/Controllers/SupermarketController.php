<?php

namespace App\Controllers;

use App\Services\SupermarketService;
use App\Validators\SupermarketRequestValidator;

class SupermarketController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->service = new SupermarketService();
        $this->validator = new SupermarketRequestValidator();
    }
}