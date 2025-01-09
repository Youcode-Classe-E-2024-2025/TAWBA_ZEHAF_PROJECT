<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';

class ProjectController {
    private $projectModel;

    public function __construct() {
        $this->projectModel = new Project();
    }

    public function index() {
        AuthMiddleware::requireLogin();
        $userId = $_SESSION['user_id'];
        $projects = $this->projectModel->getProjectsByUserId($userId);
        require_once __DIR__ . '/../Views/projects/index.php';
    }

    public function create() {
        AuthMiddleware::requireLogin(); // Vérifie si l'utilisateur est connecté
    
        // Si la requête est en POST, nous créons un projet
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données envoyées par le formulaire
            $name = $_POST['name'];
            $description = $_POST['description'];
            $userId = $_SESSION['user_id']; // ID de l'utilisateur connecté
            $isPublic = isset($_POST['is_public']) ? $_POST['is_public'] : false;
    
            // Créer un objet Project et appeler la méthode createProject pour insérer dans la base de données
            $project = new Project();
            try {
                $projectId = $project->createProject($name, $description, $userId, $isPublic);
    
                // Une fois le projet créé, rediriger vers la page du projet
                header("Location: /projects/{$projectId}");
                exit; // Ne pas oublier de stopper le script après une redirection
            } catch (\Exception $e) {
                // Si une erreur se produit lors de la création, l'afficher
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            // Si la requête est en GET, cela signifie qu'on doit afficher le formulaire de création de projet
            require_once __DIR__ . '/../Views/projects/create.php'; // Afficher le formulaire
        }
    }
    
    
    public function view($id) {
        AuthMiddleware::requireLogin();
        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            header('Location: /projects?error=Project not found');
            exit;
        }
        require_once __DIR__ . '/../Views/projects/view.php';
    }

    public function edit($id) {
        AuthMiddleware::requireLogin();
        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            header('Location: /projects?error=Project not found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $errors = ValidationHelper::validateProject($name, $description);

            if (empty($errors)) {
                if ($this->projectModel->updateProject($id, $name, $description)) {
                    header('Location: /projects');
                    exit;
                } else {
                    $errors[] = "Failed to update project";
                }
            }
        }
        require_once __DIR__ . '/../Views/projects/edit.php';
    }

    public function delete($id) {
        AuthMiddleware::requireLogin();
        if ($this->projectModel->deleteProject($id)) {
            header('Location: /projects?message=Project deleted successfully');
            exit;
        } else {
            header('Location: /projects?error=Failed to delete project');
            exit;
        }
    }

    public function search() {
        AuthMiddleware::requireLogin();
        $query = $_GET['query'] ?? '';
        $userId = $_SESSION['user_id'];
        $projects = $this->projectModel->searchProjects($query, $userId);
        require_once __DIR__ . '/../Views/projects/search.php';
    }
}