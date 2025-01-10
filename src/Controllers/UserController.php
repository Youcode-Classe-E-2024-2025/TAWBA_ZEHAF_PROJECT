<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';
require_once __DIR__ . '/../Helpers/Emailhleper.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $errors = ValidationHelper::validateRegistration($name, $email, $password);

            if (empty($errors)) {
                if ($this->userModel->register($name, $email, $password)) {
                    header('Location: /login?message=Registration successful');
                    exit;
                } else {
                    $errors[] = "Registration failed";
                }
            }
        }
        
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
        
        require_once __DIR__ . '/../Views/users/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $errors = ValidationHelper::validateLogin($email, $password);

            if (empty($errors)) {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['is_admin'] = $user['is_admin'] ?? false;
                    header('Location: /dashboard');
                    exit;
                } else {
                    $errors[] = "Invalid email or password";
                }
            }
        }
        
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
        
        require_once __DIR__ . '/../Views/users/login.php';
    }

    public function logout() {
        AuthHelper::logout();
        header('Location: /login');
        exit;
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $token = bin2hex(random_bytes(32));
                if ($this->userModel->setPasswordResetToken($email, $token)) {
                    EmailHelper::sendPasswordResetEmail($email, $token);
                    $message = "If an account exists for that email, a password reset link has been sent.";
                } else {
                    $errors[] = "Failed to process your request. Please try again.";
                }
            } else {
                $errors[] = "Invalid email address.";
            }
        }
        
        require_once __DIR__ . '/../Views/users/forgot_password.php';
    }

    public function resetPassword($token) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if ($password === $confirmPassword && strlen($password) >= 8) {
                if ($this->userModel->resetPassword($token, $password)) {
                    header('Location: /login?message=Password reset successful');
                    exit;
                } else {
                    $error = "Failed to reset password. Please try again.";
                }
            } else {
                $error = "Passwords do not match or are too short.";
            }
        }
        
        require_once __DIR__ . '/../Views/users/reset_password.php';
    }
}