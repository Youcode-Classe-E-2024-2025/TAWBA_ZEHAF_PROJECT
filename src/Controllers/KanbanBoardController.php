<?php

require_once __DIR__ . '/../Models/KanbanBoard.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';

class KanbanBoardController {
    private $kanbanBoardModel;
    private $taskModel;

    public function __construct() {
        $this->kanbanBoardModel = new KanbanBoard();
        $this->taskModel = new Task();
    }

    public function getBoard($projectId) {
        AuthHelper::requireLogin();
        $columns = $this->kanbanBoardModel->getColumnsByProjectId($projectId);
        $tasks = $this->taskModel->getTasksByProjectId($projectId);
        
        $boardData = [];
        foreach ($columns as $column) {
            $boardData[$column['id']] = [
                'name' => $column['name'],
                'tasks' => []
            ];
        }
        
        foreach ($tasks as $task) {
            $columnId = $task['column_id'] ?? $columns[0]['id'];
            $boardData[$columnId]['tasks'][] = $task;
        }
        
        return $boardData;
    }

    public function moveTask($taskId, $newColumnId) {
        AuthHelper::requireLogin();
        return $this->taskModel->updateTaskColumn($taskId, $newColumnId);
    }

    public function updateColumnOrder($columnId, $newOrder) {
        AuthHelper::requireLogin();
        return $this->kanbanBoardModel->updateColumnOrder($columnId, $newOrder);
    }
}