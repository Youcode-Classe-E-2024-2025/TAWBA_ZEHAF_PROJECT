<?php

class Role {
    private $db;
    private int $id;
    private string $name;

    public function __construct(string $name) {
        $this->db = Database::getInstance()->getConnection();
        $this->name = $name;
    }

    public function save() {
        $stmt = $this->db->prepare("INSERT INTO roles (name) VALUES (:name)");
        $stmt->execute(['name' => $this->name]);
        $this->id = $this->db->lastInsertId();
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
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

    public static function count() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM roles");
        return $stmt->fetchColumn();
    }
}