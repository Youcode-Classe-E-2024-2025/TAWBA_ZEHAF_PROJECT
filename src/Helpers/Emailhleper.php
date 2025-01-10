<?php

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
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USERNAME'];
    $mail->Password   = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $_ENV['SMTP_PORT'];

    //Recipients
    $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
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