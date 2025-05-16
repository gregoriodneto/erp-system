<?php

namespace App\Emails;

class MailManager
{
    public static function send($to, $subject, $message, $from, $message_type = "")
    {
        $htmlMessage = "
            <html>
                <head>
                    <meta charset=\"UTF-8\">
                    <title>{$subject}</title>
                </head>
                </head>
                <body>
                    <div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">
                        {$message}
                    </div>             
                </body>
            </html>
        ";

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = "From: {$from}";
        $headers[] = "Reply-To: {$from}";

        $success = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));

        if (!$success && $message_type === "debug")
        {
            error_log("Erro ao enviar e-mail para {$to}");
        }

        return $success;
    }
}