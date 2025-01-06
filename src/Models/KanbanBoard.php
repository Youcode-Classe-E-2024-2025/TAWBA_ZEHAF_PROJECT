<?php

class KanbanBoard {
    private $db;
    private $tasks = [];
    private $boardId;
    private $columns = [];
    public function __construct($boardId = null) {
        $this->boardId = $boardId;
        $this->db = Database::getInstance()->getConnection();}
   

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
    public static function findByProjectId($projectId) {
        // Get the database connection
        $db = Database::getInstance()->getConnection();

        // Query to find tasks related to the projectId (Kanban board logic)
        $query = "SELECT * FROM tasks WHERE project_id = :project_id";
        
        try {
            // Prepare and execute the query
            $stmt = $db->prepare($query);
            $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch tasks for the Kanban board
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If no tasks are found, return an empty board or a message
            if (empty($tasks)) {
                return new self(); // Empty board, no tasks
            }

            // Create a new KanbanBoard instance and add the tasks
            $kanbanBoard = new self();
            $kanbanBoard->tasks = $tasks;

            return $kanbanBoard;

        } catch (PDOException $e) {
            die("Error fetching Kanban board: " . $e->getMessage());
        }
    }
    public function createForProject($projectId) {
        // Create default tasks for the Kanban board
        $defaultTasks = [
            'To Do' => ['Task 1', 'Task 2', 'Task 3'],
            'In Progress' => ['Task 4'],
            'Completed' => ['Task 5']
        ];

        $db = Database::getInstance()->getConnection();

        try {
            // Insert the default tasks into the database
            foreach ($defaultTasks as $status => $tasks) {
                foreach ($tasks as $taskTitle) {
                    $query = "INSERT INTO tasks (title, description, status, project_id, created_by, created_at) 
                              VALUES (:title, :description, :status, :project_id, :created_by, NOW())";
                    
                    // Assume that the 'created_by' is the current logged-in user (set a user id, for now, it's 1)
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':title', $taskTitle, PDO::PARAM_STR);
                    $description = $status . ' task for project'; // Placeholder description
                    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                    $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                    $createdBy = 1; // Assume user with id 1
                    $stmt->bindParam(':created_by', $createdBy, PDO::PARAM_INT);

                    $stmt->execute();
                }
            }

            echo "Kanban board created with default tasks for project ID: " . $projectId;

        } catch (PDOException $e) {
            die("Error creating Kanban board: " . $e->getMessage());
        }
    }
    public function updateColumns($columns) {
        // Assuming $columns is an array of column names and their new order
        // Example: [ ['name' => 'To Do', 'position' => 1], ['name' => 'In Progress', 'position' => 2] ]

        // Update the columns in the database
        $pdo = new PDO('mysql:host=localhost;dbname=project_management', 'username', 'password');

        // Begin a transaction to ensure consistency
        $pdo->beginTransaction();

        try {
            // Remove all existing columns (or you can update only changed ones)
            $stmt = $pdo->prepare("DELETE FROM kanban_columns WHERE board_id = :board_id");
            $stmt->bindParam(':board_id', $this->boardId, PDO::PARAM_INT);
            $stmt->execute();

            // Insert the new columns
            $stmt = $pdo->prepare("INSERT INTO kanban_columns (board_id, name, position) VALUES (:board_id, :name, :position)");

            foreach ($columns as $index => $column) {
                $stmt->bindParam(':board_id', $this->boardId, PDO::PARAM_INT);
                $stmt->bindParam(':name', $column['name'], PDO::PARAM_STR);
                $stmt->bindParam(':position', $column['position'], PDO::PARAM_INT);
                $stmt->execute();
            }

            // Commit the transaction
            $pdo->commit();

            // Update the local columns property
            $this->columns = $columns;

        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $pdo->rollBack();
            echo "Failed to update columns: " . $e->getMessage();
        }
    }

    public function getColumns() {
        // Return the columns stored in the $columns property
        return $this->columns;
    }
}