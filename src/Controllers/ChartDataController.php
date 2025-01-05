<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Middleware\AuthMiddleware;

class ChartDataController {
    public function __construct() {
        AuthMiddleware::requireLogin();
    }

    public function getChartData() {
        $projectData = $this->getProjectData();
        $taskData = $this->getTaskData();
        $projectProgressData = $this->getProjectProgressData();
        $taskCategoryData = $this->getTaskCategoryData();

        error_log('Project Data: ' . print_r($projectData, true));
        error_log('Task Data: ' . print_r($taskData, true));
        error_log('Project Progress Data: ' . print_r($projectProgressData, true));
        error_log('Task Category Data: ' . print_r($taskCategoryData, true));

        header('Content-Type: application/json');
        echo json_encode([
            'projectData' => $projectData,
            'taskData' => $taskData,
            'projectProgressData' => $projectProgressData,
            'taskCategoryData' => $taskCategoryData
        ]);
    }

    private function getProjectData() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM projects GROUP BY status");
        $rows = $stmt->fetchAll();

        return [
            'labels' => array_column($rows, 'status'),
            'data' => array_column($rows, 'count')
        ];
    }

    private function getTaskData() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM tasks GROUP BY status");
        $rows = $stmt->fetchAll();

        return [
            'labels' => array_column($rows, 'status'),
            'data' => array_column($rows, 'count')
        ];
    }

    private function getProjectProgressData() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
            FROM projects
            WHERE status = 'completed'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month
            LIMIT 12
        ");
        $rows = $stmt->fetchAll();

        return [
            'labels' => array_column($rows, 'month'),
            'data' => array_column($rows, 'count')
        ];
    }

    private function getTaskCategoryData() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT 
                category,
                COUNT(*) as count
            FROM task_categories
            GROUP BY category
        ");
        $rows = $stmt->fetchAll();

        return [
            'labels' => array_column($rows, 'category'),
            'data' => array_column($rows, 'count')
        ];
    }
}