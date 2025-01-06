<?php

class AuthMiddleware {
    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header('Location: /dashboard');
            exit();
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
}