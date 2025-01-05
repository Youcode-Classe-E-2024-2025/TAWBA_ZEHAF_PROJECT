<?php

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';

class DashboardController {
    private $projectModel;
    private $taskModel;

    public function __construct() {
        $this->projectModel = new Project();
        $this->taskModel = new Task();
    }

    public function index() {
        AuthHelper::requireLogin();
        $userId = $_SESSION['user_id'];
        $projects = $this->projectModel->getProjectsByUserId($userId);
        $tasks = $this->taskModel->getTasksByUserId($userId);
        require_once __DIR__ . '/../Views/dashboard.php';
    }
}