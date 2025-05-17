<?php

namespace App\Emails;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailManager
{
    public static function send($to, $subject, $message, $from, $message_type = "")
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV["EMAIL_HOST"];
            $mail->SMTPAuth   = $_ENV["EMAIL_SMTP_AUTH"];
            $mail->Username   = $_ENV["EMAIL_USERNAME"];
            $mail->Password   = $_ENV["EMAIL_PASSWORD"];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV["EMAIL_PORT"];

            $mail->CharSet    = 'UTF-8';
            $mail->Encoding   = 'base64';

            $mail->setFrom($from, 'Sua Loja');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = "
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

            $mail->AltBody = strip_tags($message);
            $mail->send();
            return true;
        } catch (Exception $e) {
            if ($message_type === "debug") {
                error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
            }
            return false;
        }
    }
}