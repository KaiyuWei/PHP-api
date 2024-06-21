<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Services\UserService;

class UserController {
    private $user;

    private $userService;

    public function __construct() {
        $this->userService = new UserService();
        $this->user = new User();
    }

    public function index() {
        $users = $this->user->getAllUsers();
        ResponseHelper::sendJsonResponse($users);
    }

    public function show($id) {
        $user = $this->user->getUserById($id);
        ResponseHelper::sendJsonResponse($user);
    }

    public function store($request) {
        $this->user->createUser($request->all());
        ResponseHelper::sendJsonResponse(['message' => 'User created successfully'], 201);
    }

    public function update($id, $request) {
        $this->user->updateUser($id, $request->all());
        ResponseHelper::sendJsonResponse(['message' => 'User updated successfully']);
    }

    public function destroy($id) {
        $this->user->deleteUser($id);
        ResponseHelper::sendJsonResponse(['message' => 'User deleted successfully'], 204);
    }

    public function login($request) {
        $data = $request->all();
        if (empty($data['email']) || empty($data['password'])) {
            ResponseHelper::sendErrorJsonResponse('Email and password are required', 400);
        }

        return $this->userService->login($data['email'], $data['password']);
    }
}
