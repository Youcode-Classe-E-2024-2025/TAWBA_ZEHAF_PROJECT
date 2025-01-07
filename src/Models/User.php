<?php

require_once '../config/Database.php';


class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $hashedPassword]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
          return $user ? $user : null;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAllUsers() {
        $sql = "SELECT id, name, email FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function count() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}