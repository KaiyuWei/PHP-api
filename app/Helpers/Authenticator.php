<?php

namespace App\Helpers;

use App\Services\UserService;

class Authenticator
{
    public static function getUser(): array|false
    {
        $userService = new UserService();
        $headers = getallheaders();

        if ($headers && isset($headers['Authorization']))
        {
            $token = self::removeBearerTokenPrefix($headers['Authorization']);
            return $userService->getUser($token);
        }

        return false;
    }

    public static function isAuthenticated(): bool
    {
        $user = self::getUser();
        return (bool) $user;
    }

    public static function hasAdminRole(): bool
    {
        $user = self::getUser();
        return $user ? $user['role'] : false;
    }

    private static function removeBearerTokenPrefix(string $bearerToken): ?string
    {
        $prefix = "Bearer ";

        if (str_starts_with($bearerToken, $prefix)) {
            $bearerToken = substr($bearerToken, strlen($prefix));
        }

        return $bearerToken;
    }
}