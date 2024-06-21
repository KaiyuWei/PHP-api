<?php

namespace App\Helpers;

class ResponseHelper {
    public static function sendJsonResponse(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }

    public static function sendSuccessJsonResponse(string $message, int $status = 200): void
    {
        self::sendJsonResponse(['success' => true, 'message' => $message], $status);
    }

   public static function sendErrorJsonResponse(string $message, int $status = 400): void
   {
        self::sendJsonResponse(['success' => false, 'message' => $message], $status);
   }
}
