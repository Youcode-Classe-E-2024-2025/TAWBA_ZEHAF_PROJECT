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

   // In your DashboardController.php
public function index() {
    AuthHelper::requireLogin();
    $userId = $_SESSION['user_id'];
    $projects = $this->projectModel->getProjectsByUserId($userId);
    $tasks = $this->taskModel->getTasksByUserId($userId);

    // Add the stats for the dashboard (replace with your actual logic for stats)
    $projectStats = [
        'totalProjects' => count($projects),
    ];

    $taskStats = [
        'totalTasks' => count($tasks),
        'tasksByStatus' => [
            'Done' => count(array_filter($tasks, fn($task) => $task['status'] === 'Done')),
        ],
    ];

    // Pass both projectStats and taskStats to the view
    require_once __DIR__ . '/../Views/dashboard/dashboard.php';
}

}