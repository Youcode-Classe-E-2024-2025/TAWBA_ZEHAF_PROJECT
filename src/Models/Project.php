<?php

namespace App\Models;

class Project {
    private int $id;
    private string $name;
    private string $description;
    private int $createdBy;
    private array $teamMembers = [];

    public function __construct(string $name, string $description, int $createdBy) {
        $this->name = $name;
        $this->description = $description;
        $this->createdBy = $createdBy;
    }

    public function save(): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO projects (name, description, created_by) VALUES (:name, :description, :created_by)");
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => $this->createdBy
        ]);
        $this->id = $db->lastInsertId();
    }

    public static function findById(int $id): ?Project {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $project = $stmt->fetch();

        if (!$project) {
            return null;
        }

        $newProject = new Project($project['name'], $project['description'], $project['created_by']);
        $newProject->id = $project['id'];
        return $newProject;
    }

    public function update(): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE projects SET name = :name, description = :description WHERE id = :id");
        $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'id' => $this->id
        ]);
    }

    public static function delete(int $id): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function addTeamMember(int $userId): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO project_members (project_id, user_id) VALUES (:project_id, :user_id)");
        $stmt->execute([
            'project_id' => $this->id,
            'user_id' => $userId
        ]);
        $this->teamMembers[] = $userId;
    }

    public function removeTeamMember(int $userId): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM project_members WHERE project_id = :project_id AND user_id = :user_id");
        $stmt->execute([
            'project_id' => $this->id,
            'user_id' => $userId
        ]);
        $this->teamMembers = array_diff($this->teamMembers, [$userId]);
    }

    public function getTeamMembers(): array {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT u.* FROM users u JOIN project_members pm ON u.id = pm.user_id WHERE pm.project_id = :project_id");
        $stmt->execute(['project_id' => $this->id]);
        return $stmt->fetchAll();
    }

    public static function getAll(): array {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM projects");
        return $stmt->fetchAll();
    }

    public static function getPublicProjects(): array {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM projects WHERE is_public = 1");
        return $stmt->fetchAll();
    }
}