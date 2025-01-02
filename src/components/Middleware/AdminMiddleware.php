<?php

namespace App\Middleware;

class AdminMiddleware {
    public static function isAdmin() {
        session_start();
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
    }
}