<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/KanbanBoard.php';

class Project {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createDefaultKanbanColumns($projectId) {
        $defaultColumns = ['À faire', 'En cours', 'Terminé'];
        $kanbanBoard = new KanbanBoard();
        
        foreach ($defaultColumns as $index => $columnName) {
            $kanbanBoard->createColumn($projectId, $columnName, $index);
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $userId = $_SESSION['user_id'];  // Par exemple, en utilisant la session pour obtenir l'ID de l'utilisateur connecté
            $isPublic = isset($_POST['is_public']) ? $_POST['is_public'] : false;
        
            // Créer un objet Project et appeler la méthode create
            $project = new Project();
            try {
                $projectId = $project->createProject($name, $description, $userId, $isPublic);
                // Une fois le projet créé, rediriger vers la page du projet
                header("Location: /projects/{$projectId}");
                exit;
            } catch (\Exception $e) {
                // Gérer l'erreur (ex. afficher un message d'erreur)
                echo "Error: " . $e->getMessage();
            }
        } else {
            // Si la requête n'est pas un POST, charger un projet existant (par exemple)
            if (isset($_GET['project_id'])) {
                $projectId = $_GET['project_id'];
                $project = Project::findById($projectId); // Cette méthode doit être statique et retourner un tableau ou un objet
                if ($project) {
                    // Passer l'objet projet à la vue
                    require_once __DIR__ . '/../Views/projects/create.php';
                } else {
                    // Gérer le cas où le projet n'existe pas
                    echo "Projet non trouvé.";
                }
            }
        }
    }
    
    

    public function update($id, $name, $description, $isPublic) {
        $sql = "UPDATE projects SET name = ?, description = ?, is_public = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $description, $isPublic, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getProjectById($id) {
        $sql = "SELECT * FROM projects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProjectsByUserId($userId) {
        $sql = "SELECT * FROM projects WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicProjects() {
        $sql = "SELECT * FROM projects WHERE is_public = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM projects");
        return $stmt->fetchColumn();
    }

    public static function getAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM projects");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProject($name, $description, $isPublic, $userId) {
        $sql = "INSERT INTO projects (name, description, is_public, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $description, $isPublic, $userId]);
    }

    // public function createProject($name, $description, $userId, $isPublic = false) {
    //     $sql = "INSERT INTO projects (name, description, user_id, is_public) VALUES (?, ?, ?, ?)";
    //     $stmt = $this->db->prepare($sql);
    //     if ($stmt->execute([$name, $description, $userId, $isPublic])) {
    //         return $this->db->lastInsertId();
    //     }
    //     return false;
    // }

    public function updateProject($id, $name, $description, $isPublic = false) {
        $sql = "UPDATE projects SET name = ?, description = ?, is_public = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $description, $isPublic, $id]);
    }

    public function deleteProject($id) {
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function searchProjects($query, $userId) {
        $sql = "SELECT * FROM projects WHERE user_id = ? AND (name LIKE ? OR description LIKE ?)";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$query}%";
        $stmt->execute([$userId, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}