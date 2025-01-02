<?php

namespace App;

class ErrorHandler {
    public static function handleException(\Throwable $exception): void {
        // Log l'erreur
        error_log($exception->getMessage());

        // Affiche une page d'erreur conviviale
        http_response_code(500);
        include __DIR__ . '/Views/error.php';
    }

    public static function handleError($errno, $errstr, $errfile, $errline): bool {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}