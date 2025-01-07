<?php

require_once __DIR__ . '/../../config/Database.php';

class Task {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($title, $description, $projectId, $assignedTo, $status = 'pending') {
        $sql = "INSERT INTO tasks (title, description, project_id, assigned_to, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $description, $projectId, $assignedTo, $status]);
    }

    public function update($id, $title, $description, $assignedTo, $status) {
        $sql = "UPDATE tasks SET title = ?, description = ?, assigned_to = ?, status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $description, $assignedTo, $status, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getTaskById($id) {
        $sql = "SELECT * FROM tasks WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTasksByProjectId($projectId) {
        $sql = "SELECT * FROM tasks WHERE project_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksByUserId($userId) {
        $sql = "SELECT * FROM tasks WHERE assigned_to = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE tasks SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function updateTaskColumn($taskId, $columnId) {
        $sql = "UPDATE tasks SET column_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$columnId, $taskId]);
    }

    public function getByProjectId($projectId) {
        $sql = "SELECT * FROM tasks WHERE project_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertedId() {
        return $this->db->lastInsertId();
    }
}