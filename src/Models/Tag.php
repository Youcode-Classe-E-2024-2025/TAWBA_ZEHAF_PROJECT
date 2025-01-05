<?php
namespace Config; 
class Tag {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($name) {
        $sql = "INSERT INTO tags (name) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name]);
    }

    public function getAll() {
        $sql = "SELECT * FROM tags";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addTaskTag($taskId, $tagId) {
        $sql = "INSERT INTO task_tags (task_id, tag_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$taskId, $tagId]);
    }

    public function getTaskTags($taskId) {
        $sql = "SELECT t.* FROM tags t
                JOIN task_tags tt ON t.id = tt.tag_id
                WHERE tt.task_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$taskId]);
        return $stmt->fetchAll();
    }

    public function removeTaskTags($taskId) {
        $sql = "DELETE FROM task_tags WHERE task_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$taskId]);
    }
}