<?php

class ActivityLog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function log($userId, $projectId, $action) {
        $sql = "INSERT INTO activity_log (user_id, project_id, action) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $projectId, $action]);
    }

    public function getProjectActivities($projectId) {
        $sql = "SELECT al.*, u.name as user_name 
                FROM activity_log al
                JOIN users u ON al.user_id = u.id
                WHERE al.project_id = ?
                ORDER BY al.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

