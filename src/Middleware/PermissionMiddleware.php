<?php

class PermissionMiddleware {
    public static function requirePermission($permission) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user = new User();
        if (!$user->hasPermission($_SESSION['user_id'], $permission)) {
            header('Location: /dashboard?error=Insufficient permissions');
            exit;
        }
    }
}

