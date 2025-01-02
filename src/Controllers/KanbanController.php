<?php

namespace App\Controllers;

use App\Models\KanbanBoard;
use App\Models\Task;
use App\Models\Project;
use App\Middleware\AuthMiddleware;

class KanbanController {
    public function __construct() {
        AuthMiddleware::requireLogin();
    }

    public function show(int $projectId) {
        $project = Project::findById($projectId);
        if (!$project) {
            header('Location: /projects?error=Project not found');
            exit;
        }

        $kanbanBoard = KanbanBoard::findByProjectId($projectId);
        if (!$kanbanBoard) {
            $kanbanBoard = new KanbanBoard($projectId);
            $kanbanBoard->save();
        }

        $tasks = Task::getByProjectId($projectId);
        $columns = $kanbanBoard->getColumns();

        require_once __DIR__ . '/../Views/kanban/show.php';
    }

    public function updateTaskColumn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'] ?? null;
            $newColumn = $_POST['new_column'] ?? null;

            if ($taskId && $newColumn) {
                $task = Task::findById($taskId);
                if ($task) {
                    $task->updateColumn($newColumn);
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }

        echo json_encode(['success' => false]);
        exit;
    }

    public function updateColumns(int $projectId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $columns = $_POST['columns'] ?? null;

            if ($columns) {
                $kanbanBoard = KanbanBoard::findByProjectId($projectId);
                if ($kanbanBoard) {
                    $kanbanBoard->updateColumns($columns);
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }

        echo json_encode(['success' => false]);
        exit;
    }
}