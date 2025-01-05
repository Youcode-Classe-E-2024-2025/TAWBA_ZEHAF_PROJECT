<?php
namespace Config; 
class KanbanBoard {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getColumnsByProjectId($projectId) {
        $sql = "SELECT * FROM kanban_columns WHERE project_id = ? ORDER BY `order`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }

    public function createColumn($projectId, $name, $order) {
        $sql = "INSERT INTO kanban_columns (project_id, name, `order`) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$projectId, $name, $order]);
    }

    public function updateColumnOrder($columnId, $newOrder) {
        $sql = "UPDATE kanban_columns SET `order` = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newOrder, $columnId]);
    }

    public function deleteColumn($columnId) {
        $sql = "DELETE FROM kanban_columns WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$columnId]);
    }
}