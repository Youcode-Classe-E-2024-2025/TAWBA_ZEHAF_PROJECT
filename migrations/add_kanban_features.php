<?php

$db = Database::getInstance()->getConnection();

// Create kanban_boards table
$db->exec("CREATE TABLE IF NOT EXISTS kanban_boards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    columns JSON NOT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
)");

// Add column field to tasks table
$db->exec("ALTER TABLE tasks ADD COLUMN column VARCHAR(255) DEFAULT 'To Do'");

echo "Kanban features migration completed successfully.\n";