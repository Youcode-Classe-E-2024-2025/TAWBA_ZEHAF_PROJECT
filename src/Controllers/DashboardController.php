<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Middleware\AuthMiddleware;

class DashboardController {
    public function __construct() {
        AuthMiddleware::requireLogin();
    }

    public function index() {
        $userId = $_SESSION['user_id'];

        // Fetch projects and tasks
        $projects = Project::getByUserId($userId);
        $tasks = Task::getByUserId($userId);

        // Prepare data for charts
        $projectStats = $this->getProjectStats($projects);
        $taskStats = $this->getTaskStats($tasks);

        // Pass data to the view
        require_once __DIR__ . '/../Views/dashboard/dashboard.php';
    }

    private function getProjectStats($projects) {
        $stats = [
            'totalProjects' => count($projects),
            'projectsByStatus' => [
                'Not Started' => 0,
                'In Progress' => 0,
                'Completed' => 0
            ]
        ];

        foreach ($projects as $project) {
            $stats['projectsByStatus'][$project['status']]++;
        }

        return $stats;
    }

    private function getTaskStats($tasks) {
        $stats = [
            'totalTasks' => count($tasks),
            'tasksByStatus' => [
                'To Do' => 0,
                'In Progress' => 0,
                'Done' => 0
            ]
        ];

        foreach ($tasks as $task) {
            $stats['tasksByStatus'][$task['status']]++;
        }

        return $stats;
    }
}