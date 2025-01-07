<?php

require_once __DIR__ . '/../Models/KanbanBoard.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';

class KanbanController {
    private $kanbanBoardModel;
    private $taskModel;
    private $projectModel;

    public function __construct() {
        $this->kanbanBoardModel = new KanbanBoard();
        $this->taskModel = new Task();
        $this->projectModel = new Project();
    }

    public function show($projectId) {
        AuthHelper::requireAuth();
        $project = Project::findById($projectId);
        if (!$project) {
            header('Location: /projects?error=Project not found');
            exit;
        }

        $kanbanBoard = KanbanBoard::findByProjectId($projectId);
        if (!$kanbanBoard) {
            $kanbanBoard = new KanbanBoard();
            $kanbanBoard->createForProject($projectId);
        }

        $taskInstance = new Task();
        $tasks = $taskInstance->getByProjectId($projectId);
        $columns = $kanbanBoard->getColumns();

        require_once __DIR__ . '/../Views/kanban/show.php';
    }

    public function updateTaskColumn() {
        AuthHelper::requireAuth();
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

    public function updateColumns($projectId) {
        AuthHelper::requireAuth();
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