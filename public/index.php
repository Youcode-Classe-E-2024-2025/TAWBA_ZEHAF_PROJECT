<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/ProjectController.php';
require_once __DIR__ . '/../src/Controllers/TaskController.php';
require_once __DIR__ . '/../src/Controllers/DashboardController.php';
require_once __DIR__ . '/../src/Controllers/AdminController.php'; // Added require for AdminController
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple routing
switch (true) {
    case $uri === '/' || $uri === '/dashboard':
        AuthMiddleware::requireLogin();
        $controller = new DashboardController();
        $controller->index();
        break;

    case $uri === '/login':
        $controller = new UserController();
        $controller->login();
        break;

    case $uri === '/register':
        $controller = new UserController();
        $controller->register();
        break;

    case $uri === '/logout':
        $controller = new UserController();
        $controller->logout();
        break;

    case $uri === '/forgot-password':
        $controller = new UserController();
        $controller->forgotPassword();
        break;

    case preg_match('/^\/reset-password\/([^\/]+)$/', $uri, $matches):
        $controller = new UserController();
        $controller->resetPassword($matches[1]);
        break;

    case $uri === '/projects':
        AuthMiddleware::requireLogin();
        $controller = new ProjectController();
        $controller->index();
        break;

    case $uri === '/projects/create':
        AuthMiddleware::requireLogin();
        $controller = new ProjectController();
        $controller->create();
        break;

    case preg_match('/^\/projects\/edit\/(\d+)$/', $uri, $matches):
        AuthMiddleware::requireLogin();
        $controller = new ProjectController();
        $controller->edit($matches[1]);
        break;

    case preg_match('/^\/projects\/delete\/(\d+)$/', $uri, $matches):
        AuthMiddleware::requireLogin();
        $controller = new ProjectController();
        $controller->delete($matches[1]);
        break;

    case preg_match('/^\/projects\/view\/(\d+)$/', $uri, $matches):
        AuthMiddleware::requireLogin();
        $controller = new ProjectController();
        $controller->view($matches[1]);
        break;

    case preg_match('/^\/tasks\/create\/(\d+)$/', $uri, $matches):
        AuthMiddleware::requireLogin();
        $controller = new TaskController();
        $controller->create($matches[1]);
        break;

    case preg_match('/^\/tasks\/edit\/(\d+)$/', $uri, $matches):
        AuthMiddleware::requireLogin();
        $controller = new TaskController();
        $controller->edit($matches[1]);
        break;

    // Admin login route
    case $uri === '/admin/login':
        $controller = new AdminController();
        $controller->login();
        break;

    // Admin dashboard route
    case $uri === '/admin/dashboard':
        AuthMiddleware::requireAdmin();
        $controller = new AdminController();
        $controller->dashboard();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../src/Views/errors/404.php';
        break;
}