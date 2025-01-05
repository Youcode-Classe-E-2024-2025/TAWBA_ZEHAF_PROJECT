<?php

namespace App\Models;
namespace Config; 
use PDO;

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
return false;
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

// Static methods that might be needed for your AdminController
public static function count() {
$db = Database::getInstance()->getConnection();
$sql = "SELECT COUNT(*) FROM users";
$stmt = $db->prepare($sql);
$stmt->execute();
return $stmt->fetchColumn();
}

public static function getAll() {
$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM users";
$stmt = $db->prepare($sql);
$stmt->execute();
return $stmt->fetchAll();
}

public static function findById($id) {
$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id]);
return $stmt->fetch();
}

public static function delete($id) {
$db = Database::getInstance()->getConnection();
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
return $stmt->execute([$id]);
}
}