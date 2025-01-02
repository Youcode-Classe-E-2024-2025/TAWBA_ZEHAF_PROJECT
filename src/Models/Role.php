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

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }
}