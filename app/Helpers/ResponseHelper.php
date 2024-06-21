<?php

namespace App\Helpers;

class ResponseHelper {
    public static function sendJsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }

    public static function sendSuccessJsonResponse($message, $status = 200) {
        self::sendJsonResponse(['success' => true, 'message' => $message], $status);
    }

   public static function sendErrorJsonResponse($message, $status = 400) {
        self::sendJsonResponse(['success' => false, 'message' => $message], $status);
    }
}
