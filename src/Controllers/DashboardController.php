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
        
        try {
            $projects = $this->projectModel->getProjectsByUserId($userId);
            $tasks = $this->taskModel->getTasksByUserId($userId);
        } catch (PDOException $e) {
            // Log the error
            error_log("Database error: " . $e->getMessage());
            // Set error message to display to the user
            $error = "An error occurred while fetching your data. Please try again later.";
            $projects = [];
            $tasks = [];
        }

        $projectStats = [
            'totalProjects' => count($projects),
            'inProgress' => 0,
            'completed' => 0,
            'onHold' => 0
        ];

        foreach ($projects as $project) {
            switch ($project['status']) {
                case 'In Progress':
                    $projectStats['inProgress']++;
                    break;
                case 'Completed':
                    $projectStats['completed']++;
                    break;
                case 'On Hold':
                    $projectStats['onHold']++;
                    break;
            }
        }

        $taskStats = [
            'totalTasks' => count($tasks),
            'tasksByStatus' => [
                'Done' => count(array_filter($tasks, fn($task) => $task['status'] === 'Done')),
            ],
        ];

        require_once __DIR__ . '/../Views/dashboard/dashboard.php';
    }
}