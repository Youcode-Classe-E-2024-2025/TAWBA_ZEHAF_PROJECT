<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/ProjectController.php';
require_once __DIR__ . '/../src/Controllers/TaskController.php';
require_once __DIR__ . '/../src/Controllers/DashboardController.php';
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple routing
switch (true) {
    case $uri === '/':
    case $uri === '/dashboard':
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

    case $uri === '/projects':
        $controller = new ProjectController();
        $controller->index();
        break;

    case $uri === '/projects/create':
        $controller = new ProjectController();
        $controller->create();
        break;

    case preg_match('/^\/projects\/edit\/(\d+)$/', $uri, $matches):
        $controller = new ProjectController();
        $controller->edit($matches[1]);
        break;

    case preg_match('/^\/projects\/delete\/(\d+)$/', $uri, $matches):
        $controller = new ProjectController();
        $controller->delete($matches[1]);
        break;

    case preg_match('/^\/projects\/view\/(\d+)$/', $uri, $matches):
        $controller = new ProjectController();
        $controller->view($matches[1]);
        break;

    case preg_match('/^\/tasks\/create\/(\d+)$/', $uri, $matches):
        $controller = new TaskController();
        $controller->create($matches[1]);
        break;

    case preg_match('/^\/tasks\/edit\/(\d+)$/', $uri, $matches):
        $controller = new TaskController();
        $controller->edit($matches[1]);
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../src/Views/errors/404.php';
        break;
}