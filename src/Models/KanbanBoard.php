<?php

namespace App\Models;

class KanbanBoard {
    private int $id;
    private int $projectId;
    private array $columns;

    public function __construct(int $projectId) {
        $this->projectId = $projectId;
        $this->columns = ['To Do', 'In Progress', 'Done'];
    }

    public function save(): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO kanban_boards (project_id, columns) VALUES (:project_id, :columns)");
        $stmt->execute([
            'project_id' => $this->projectId,
            'columns' => json_encode($this->columns)
        ]);
        $this->id = $db->lastInsertId();
    }

    public static function findByProjectId(int $projectId): ?KanbanBoard {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM kanban_boards WHERE project_id = :project_id");
        $stmt->execute(['project_id' => $projectId]);
        $board = $stmt->fetch();

        if (!$board) {
            return null;
        }

        $kanbanBoard = new KanbanBoard($board['project_id']);
        $kanbanBoard->id = $board['id'];
        $kanbanBoard->columns = json_decode($board['columns'], true);
        return $kanbanBoard;
    }

    public function updateColumns(array $columns): void {
        $this->columns = $columns;
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE kanban_boards SET columns = :columns WHERE id = :id");
        $stmt->execute([
            'columns' => json_encode($this->columns),
            'id' => $this->id
        ]);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getProjectId(): int {
        return $this->projectId;
    }

    public function getColumns(): array {
        return $this->columns;
    }
}