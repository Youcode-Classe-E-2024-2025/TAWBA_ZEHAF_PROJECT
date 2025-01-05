<?php

namespace App\Controllers;

use App\Models\KanbanBoard;
use App\Models\Task;
use App\Models\Project;
use App\Middleware\AuthMiddleware;

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/KanbanBoard.php';

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
            $newColumnId = $_POST['new_column'] ?? null;

            if ($taskId && $newColumnId) {
                $task = new Task();
                $result = $task->updateTaskColumn($taskId, $newColumnId);
                echo json_encode(['success' => $result]);
                exit;
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