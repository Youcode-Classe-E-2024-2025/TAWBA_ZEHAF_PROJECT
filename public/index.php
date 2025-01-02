<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Project.php';
require_once __DIR__ . '/../src/Models/Task.php';
require_once __DIR__ . '/../src/Models/Category.php';
require_once __DIR__ . '/../src/Models/Tag.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/ProjectController.php';
require_once __DIR__ . '/../src/Controllers/TaskController.php';

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple routing
switch (true) {
    case $uri === '/':
        header('Location: /login');
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

    case preg_match('/^\/tasks\/update-status\/(\d+)\/(\w+)$/', $uri, $matches):
        $controller = new TaskController();
        $controller->updateStatus($matches[1], $matches[2]);
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}