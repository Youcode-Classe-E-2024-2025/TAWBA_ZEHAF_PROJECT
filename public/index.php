<?php
use App\Controllers\ProjectController;
use App\Controllers\TaskController;

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration du gestionnaire d'erreurs
set_exception_handler(['\App\ErrorHandler', 'handleException']);
set_error_handler(['\App\ErrorHandler', 'handleError']);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Routage simple
switch (true) {
    case $uri === '/':
        header('Location: /login');
        exit;
        break;

    case $uri === '/login':
        $controller = new \App\Controllers\UserController();
        $controller->login();
        break;

    case $uri === '/register':
        $controller = new \App\Controllers\UserController();
        $controller->register();
        break;

    case $uri === '/logout':
        $controller = new \App\Controllers\UserController();
        $controller->logout();
        break;

    case preg_match('/^\/verify-email\/([^\/]+)$/', $uri, $matches):
        $controller = new \App\Controllers\UserController();
        $controller->verifyEmail($matches[1]);
        break;

    case $uri === '/request-password-reset':
        $controller = new \App\Controllers\UserController();
        $controller->requestPasswordReset();
        break;

    case preg_match('/^\/reset-password\/([^\/]+)$/', $uri, $matches):
        $controller = new \App\Controllers\UserController();
        $controller->resetPassword($matches[1]);
        break;

    case $uri === '/projects':
        $controller = new \App\Controllers\ProjectController();
        $controller->index();
        break;

    case $uri === '/projects/create':
        $controller = new \App\Controllers\ProjectController();
        $controller->create();
        break;

    case preg_match('/^\/projects\/(\d+)$/', $uri, $matches):
        $controller = new \App\Controllers\ProjectController();
        $controller->view($matches[1]);
        break;

    case preg_match('/^\/projects\/(\d+)\/edit$/', $uri, $matches):
        $controller = new \App\Controllers\ProjectController();
        $controller->edit($matches[1]);
        break;

    case preg_match('/^\/projects\/(\d+)\/delete$/', $uri, $matches):
        $controller = new \App\Controllers\ProjectController();
        $controller->delete($matches[1]);
        break;

    case preg_match('/^\/projects\/(\d+)\/add-team-member$/', $uri, $matches):
        $controller = new \App\Controllers\ProjectController();
        $controller->addTeamMember($matches[1]);
        break;

    case preg_match('/^\/projects\/(\d+)\/remove-team-member\/(\d+)$/', $uri, $matches):
        $controller = new \App\Controllers\ProjectController();
        $controller->removeTeamMember($matches[1], $matches[2]);
        break;

    case preg_match('/^\/projects\/(\d+)\/tasks\/create$/', $uri, $matches):
        $controller = new \App\Controllers\TaskController();
        $controller->create($matches[1]);
        break;

    case preg_match('/^\/tasks\/(\d+)\/edit$/', $uri, $matches):
        $controller = new \App\Controllers\TaskController();
        $controller->edit($matches[1]);
        break;

    case preg_match('/^\/tasks\/(\d+)\/delete$/', $uri, $matches):
        $controller = new \App\Controllers\TaskController();
        $controller->delete($matches[1]);
        break;

    case $uri === '/dashboard':
        $controller = new \App\Controllers\DashboardController();
        $controller->index();
        break;

    case $uri === '/admin':
        $controller = new \App\Controllers\AdminController();
        $controller->index();
        break;

    case $uri === '/admin/users':
        $controller = new \App\Controllers\AdminController();
        $controller->users();
        break;

    case preg_match('/^\/admin\/users\/(\d+)\/edit$/', $uri, $matches):
        $controller = new \App\Controllers\AdminController();
        $controller->editUser($matches[1]);
        break;

    case preg_match('/^\/admin\/users\/(\d+)\/delete$/', $uri, $matches):
        $controller = new \App\Controllers\AdminController();
        $controller->deleteUser($matches[1]);
        break;

    case $uri === '/admin/projects':
        $controller = new \App\Controllers\AdminController();
        $controller->projects();
        break;

    case preg_match('/^\/admin\/projects\/(\d+)\/edit$/', $uri, $matches):
        $controller = new \App\Controllers\AdminController();
        $controller->editProject($matches[1]);
        break;

    case preg_match('/^\/admin\/projects\/(\d+)\/delete$/', $uri, $matches):
        $controller = new \App\Controllers\AdminController();
        $controller->deleteProject($matches[1]);
        break;

    case preg_match('/^\/projects\/(\d+)\/kanban$/', $uri, $matches):
        $controller = new \App\Controllers\KanbanController();
        $controller->show($matches[1]);
        break;

    case $uri === '/kanban/update-task-column':
        $controller = new \App\Controllers\KanbanController();
        $controller->updateTaskColumn();
        break;

    case preg_match('/^\/projects\/(\d+)\/kanban\/update-columns$/', $uri, $matches):
        $controller = new \App\Controllers\KanbanController();
        $controller->updateColumns($matches[1]);
        break;

    case $uri === '/api/chart-data':
        $controller = new \App\Controllers\ChartDataController();
        $controller->getChartData();
        break;

    default:
        // Gérer les autres routes ou afficher une page 404
        http_response_code(404);
        echo "404 Not Found";
        break;
}