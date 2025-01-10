<?php

// Helper functions

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return APP_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        header("Location: " . base_url($path));
        exit;
    }
}