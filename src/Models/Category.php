<?php
namespace Config;
namespace App\Models;

use App\Database;
class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($name) {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name]);
    }

    public function getAll() {
        $sql = "SELECT * FROM categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addTaskCategory($taskId, $categoryId) {
        $sql = "INSERT INTO task_categories (task_id, category_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$taskId, $categoryId]);
    }

    public function getTaskCategories($taskId) {
        $sql = "SELECT c.* FROM categories c
                JOIN task_categories tc ON c.id = tc.category_id
                WHERE tc.task_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$taskId]);
        return $stmt->fetchAll();
    }

    public function removeTaskCategories($taskId) {
        $sql = "DELETE FROM task_categories WHERE task_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$taskId]);
    }
}