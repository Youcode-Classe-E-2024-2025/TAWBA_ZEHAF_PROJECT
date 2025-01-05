<?php

class ValidationHelper {
    public static function validateRegistration($name, $email, $password) {
        $errors = [];

        if (empty($name) || strlen($name) < 2 || strlen($name) > 50) {
            $errors[] = "Name must be between 2 and 50 characters";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        return $errors;
    }

    public static function validateLogin($email, $password) {
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        return $errors;
    }

    public static function validateProject($name, $description) {
        $errors = [];

        if (empty($name) || strlen($name) < 2 || strlen($name) > 100) {
            $errors[] = "Project name must be between 2 and 100 characters";
        }

        if (empty($description)) {
            $errors[] = "Project description is required";
        }

        return $errors;
    }

    public static function validateTask($title, $description) {
        $errors = [];

        if (empty($title) || strlen($title) < 2 || strlen($title) > 100) {
            $errors[] = "Task title must be between 2 and 100 characters";
        }

        if (empty($description)) {
            $errors[] = "Task description is required";
        }

        return $errors;
    }
}