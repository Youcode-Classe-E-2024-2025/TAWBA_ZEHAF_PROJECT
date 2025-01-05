<?php

namespace App\Middleware;

class AdminMiddleware {
    public static function requireAdmin() {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is an admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Redirect to a login page or deny access
            header('Location: /login');
            exit;
        }
    }
    public static function isAdmin() {
        session_start();
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
    }}