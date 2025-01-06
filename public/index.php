<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/ProjectController.php';
require_once __DIR__ . '/../src/Controllers/TaskController.php';
require_once __DIR__ . '/../src/Controllers/DashboardController.php';
require_once __DIR__ . '/../src/Controllers/KanbanController.php';
require_once __DIR__ . '/../src/Controllers/AdminController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple routing
switch (true) {
    case $uri === '/':
        case $uri === '/register':
            $controller = new UserController();
            $controller->register();
            break;
        case $uri === '/login':
            echo "Login route accessed"; 
            $controller = new UserController();
            $controller->login();
            break;
        case $uri === '/projects':
                $controller = new ProjectController();
                $controller->index();
                break;
                case $uri === '/projects/create':
                    $controller = new ProjectController();
                    $controller->create();
                    break;
            
    case $uri === '/dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

        
  
    case $uri === '/logout':
        $controller = new UserController();
        $controller->logout();
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

    case preg_match('/^\/projects\/kanban\/(\d+)$/', $uri, $matches):
        $controller = new KanbanController();
        $controller->show($matches[1]);
        break;

    case preg_match('/^\/tasks\/create\/(\d+)$/', $uri, $matches):
        $controller = new TaskController();
        $controller->create($matches[1]);
        break;

    case preg_match('/^\/tasks\/edit\/(\d+)$/', $uri, $matches):
        $controller = new TaskController();
        $controller->edit($matches[1]);
        break;

    case $uri === '/tasks/move':
        $controller = new TaskController();
        $controller->moveTask();
        break;

    case $uri === '/admin':
        $controller = new AdminController();
        $controller->index();
        break;

    case $uri === '/admin/users':
        $controller = new AdminController();
        $controller->users();
        break;

    case preg_match('/^\/admin\/users\/edit\/(\d+)$/', $uri, $matches):
        $controller = new AdminController();
        $controller->editUser($matches[1]);
        break;

    case preg_match('/^\/admin\/users\/delete\/(\d+)$/', $uri, $matches):
        $controller = new AdminController();
        $controller->deleteUser($matches[1]);
        break;

    case $uri === '/admin/projects':
        $controller = new AdminController();
        $controller->projects();
        break;

    case preg_match('/^\/admin\/projects\/edit\/(\d+)$/', $uri, $matches):
        $controller = new AdminController();
        $controller->editProject($matches[1]);
        break;

    case preg_match('/^\/admin\/projects\/delete\/(\d+)$/', $uri, $matches):
        $controller = new AdminController();
        $controller->deleteProject($matches[1]);
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../src/Views/404.php';

        break;
}