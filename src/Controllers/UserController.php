<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\EmailHelper;

class UserController {
    private function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function register() {
        $csrfToken = $this->generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCSRFToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            // Using FILTER_SANITIZE_SPECIAL_CHARS instead of deprecated FILTER_SANITIZE_STRING
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Server-side validation
            $errors = [];
            if (empty($name) || !preg_match('/^[a-zA-Z ]{2,100}$/', $name)) {
                $errors[] = "Name must be between 2 and 100 characters and contain only letters and spaces.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
                $errors[] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
            }

            if (empty($errors)) {
                $user = new User($name, $email, $password, 2); // Assuming 2 is the role_id for regular users
                $user->save();

                // Send verification email
                EmailHelper::sendVerificationEmail($user->getEmail(), $user->getVerificationToken());

                // Redirect to login page with a message
                header('Location: /login?message=' . urlencode('Please check your email to verify your account'));
                exit;
            }
        }

        require_once __DIR__ . '/../Views/users/register.php';
    }

    public function login() {
        $csrfToken = $this->generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCSRFToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Server-side validation
            $errors = [];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (empty($password)) {
                $errors[] = "Password is required.";
            }

            if (empty($errors)) {
                $user = User::findByEmailWithRole($email);

                if ($user && password_verify($password, $user['password'])) {
                    if (!$user['is_verified']) {
                        $errors[] = "Please verify your email before logging in.";
                    } else {
                        // Start the session and store user information
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_role'] = $user['role_name'];

                        if ($user['role_name'] === 'admin') {
                            $_SESSION['is_admin'] = true;
                            header('Location: /admin/dashboard');
                        } else {
                            $_SESSION['is_admin'] = false;
                            header('Location: /dashboard');
                        }
                        exit;
                    }
                } else {
                    $errors[] = "Invalid email or password";
                }
            }
        }

        require_once __DIR__ . '/../Views/users/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function verifyEmail($token) {
        if (User::verifyEmail($token)) {
            header('Location: /login?message=Email verified successfully. You can now log in.');
        } else {
            header('Location: /login?error=Invalid or expired verification token');
        }
        exit;
    }

    public function requestPasswordReset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $token = User::requestPasswordReset($email);
            if ($token) {
                EmailHelper::sendPasswordResetEmail($email, $token);
                header('Location: /login?message=Password reset instructions sent to your email');
            } else {
                header('Location: /login?error=Email not found');
            }
            exit;
        }
        require_once __DIR__ . '/../Views/users/request_password_reset.php';
    }

    public function resetPassword($token) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($password !== $confirmPassword) {
                $error = "Passwords do not match";
            } elseif (strlen($password) < 8) {
                $error = "Password must be at least 8 characters long";
            } else {
                if (User::resetPassword($token, $password)) {
                    header('Location: /login?message=Password reset successfully. You can now log in with your new password.');
                    exit;
                } else {
                    $error = "Invalid or expired token";
                }
            }
        }
        require_once __DIR__ . '/../Views/users/reset_password.php';
    }
}