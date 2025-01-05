<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper
{
    public static function sendVerificationEmail(string $email, string $token): bool
    {
        $subject = "Verify Your Email Address";
        $body = "Please click the following link to verify your email address: " .
                "http://yourdomain.com/verify-email/{$token}";

        return self::sendEmail($email, $subject, $body);
    }

    public static function sendPasswordResetEmail(string $email, string $token): bool
    {
        $subject = "Reset Your Password";
        $body = "Please click the following link to reset your password: " .
                "http://yourdomain.com/reset-password/{$token}";

        return self::sendEmail($email, $subject, $body);
    }

    private static function sendEmail(string $to, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_username';
            $mail->Password   = 'your_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('from@example.com', 'Your Name');
            $mail->addAddress($to);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}