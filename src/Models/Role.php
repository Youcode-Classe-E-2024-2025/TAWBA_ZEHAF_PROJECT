<?php

namespace App\Models;

class Role {
    private int $id;
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function save() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO roles (name) VALUES (:name)");
        $stmt->execute(['name' => $this->name]);
        $this->id = $db->lastInsertId();
    }

    public static function getAll() {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM roles");
        return $stmt->fetchAll();
    }

}