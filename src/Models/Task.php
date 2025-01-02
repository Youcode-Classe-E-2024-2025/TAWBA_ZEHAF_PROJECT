<?php

namespace App\Models;

class Task {
    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private int $projectId;
    private ?int $assignedTo;
    private int $createdBy;
    private array $categories = [];
    private array $tags = [];
    private string $column;

    public function __construct(string $title, string $description, int $projectId, int $createdBy, ?int $assignedTo = null, string $column = 'To Do') {
        $this->title = $title;
        $this->description = $description;
        $this->status = 'pending';
        $this->projectId = $projectId;
        $this->createdBy = $createdBy;
        $this->assignedTo = $assignedTo;
        $this->column = $column;
    }

    public function save(): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO tasks (title, description, status, project_id, assigned_to, created_by, column) VALUES (:title, :description, :status, :project_id, :assigned_to, :created_by, :column)");
        $stmt->execute([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'project_id' => $this->projectId,
            'assigned_to' => $this->assignedTo,
            'created_by' => $this->createdBy,
            'column' => $this->column
        ]);
        $this->id = $db->lastInsertId();
    }

    public static function findById(int $id): ?Task {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $task = $stmt->fetch();

        if (!$task) {
            return null;
        }

        $newTask = new Task($task['title'], $task['description'], $task['project_id'], $task['created_by'], $task['assigned_to'], $task['column']);
        $newTask->id = $task['id'];
        $newTask->status = $task['status'];
        return $newTask;
    }

    public function update(): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE tasks SET title = :title, description = :description, status = :status, assigned_to = :assigned_to, column = :column WHERE id = :id");
        $stmt->execute([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'assigned_to' => $this->assignedTo,
            'column' => $this->column,
            'id' => $this->id
        ]);
    }

    public function updateColumn(string $column): void {
        $this->column = $column;
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE tasks SET column = :column WHERE id = :id");
        $stmt->execute([
            'column' => $this->column,
            'id' => $this->id
        ]);
    }

    public function addCategory(string $category): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO task_categories (task_id, category) VALUES (:task_id, :category)");
        $stmt->execute([
            'task_id' => $this->id,
            'category' => $category
        ]);
        $this->categories[] = $category;
    }

    public function removeCategory(string $category): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM task_categories WHERE task_id = :task_id AND category = :category");
        $stmt->execute([
            'task_id' => $this->id,
            'category' => $category
        ]);
        $this->categories = array_diff($this->categories, [$category]);
    }

    public function addTag(string $tag): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO task_tags (task_id, tag) VALUES (:task_id, :tag)");
        $stmt->execute([
            'task_id' => $this->id,
            'tag' => $tag
        ]);
        $this->tags[] = $tag;
    }

    public function removeTag(string $tag): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM task_tags WHERE task_id = :task_id AND tag = :tag");
        $stmt->execute([
            'task_id' => $this->id,
            'tag' => $tag
        ]);
        $this->tags = array_diff($this->tags, [$tag]);
    }

    public static function getByProjectId(int $projectId): array {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM tasks WHERE project_id = :project_id");
        $stmt->execute(['project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    public static function getByUserId(int $userId): array {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM tasks WHERE assigned_to = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getColumn(): string {
        return $this->column;
    }

    public function setColumn(string $column): void {
        $this->column = $column;
    }
}