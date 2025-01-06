<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/KanbanBoard.php';

class Project {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createDefaultKanbanColumns($projectId) {
        $defaultColumns = ['À faire', 'En cours', 'Terminé'];
        $kanbanBoard = new KanbanBoard();
        
        foreach ($defaultColumns as $index => $columnName) {
            $kanbanBoard->createColumn($projectId, $columnName, $index);
        }
    }

    public function create($name, $description, $userId, $isPublic = false) {
        $this->db->beginTransaction();
        
        try {
            $sql = "INSERT INTO projects (name, description, user_id, is_public) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name, $description, $userId, $isPublic]);
            
            $projectId = $this->db->lastInsertId();
            $this->createDefaultKanbanColumns($projectId);
            
            $this->db->commit();
            return $projectId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update($id, $name, $description, $isPublic) {
        $sql = "UPDATE projects SET name = ?, description = ?, is_public = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $description, $isPublic, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getProjectById($id) {
        $sql = "SELECT * FROM projects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getProjectsByUserId($userId) {
        // $sql = "SELECT * FROM projects WHERE user_id = ?";
        $sql = "SELECT * FROM projects WHERE created_by = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getPublicProjects() {
        $sql = "SELECT * FROM projects WHERE is_public = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function count() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM projects");
        return $stmt->fetchColumn();
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM projects");
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    
}