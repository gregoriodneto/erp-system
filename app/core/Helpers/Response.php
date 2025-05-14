<?php

namespace App\Core\Helpers;

class Response
{
    public static function json(array $data = [], int $status = 200)
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    public static function success($message = 'Success', $data = [])
    {
        self::json([
            'success' => true,
            'message'=> $message,
            'data'=> $data,
        ]);
    }

    public static function error($message = 'An error occurred', int $status = 400)
    {
        self::json([
            'success'=> false,
            'error'=> $message,
        ], $status);
    }
}