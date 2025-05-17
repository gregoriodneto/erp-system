<?php
namespace App\Middlewares;

use App\Core\Helpers\Response;

class AuthWebhook
{
    public static function verity()
    {
        $headers = getallheaders();
        $auth_headers = $headers["Authorization"] ?? '';

        if (!preg_match('/Bearer\s+(.+)/', $auth_headers, $matches)) 
        {
            Response::error('Token de autenticação ausente', 401);
        }

        $token = $matches[1];
        $expectedToken = $_ENV['WEBHOOK_TOKEN'] ??'';

        if ($token !== $expectedToken)
        {
            Response::error('Token Inválido.',403);
        }
    }
}