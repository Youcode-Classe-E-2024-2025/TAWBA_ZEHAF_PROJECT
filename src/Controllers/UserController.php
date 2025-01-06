<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';

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
                    header('Location: /dashboard');
                    exit;
                } else {
                    $errors[] = "Invalid email or password";
                }
            }
        }
        require_once __DIR__ . '/../Views/users/login.php';
    }

    public function logout() {
        AuthHelper::logout();
        header('Location: /login');
        exit;
    }
}