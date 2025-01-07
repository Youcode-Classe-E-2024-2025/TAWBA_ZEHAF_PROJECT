<?php

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
        AuthMiddleware::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $userId = $_SESSION['user_id'];

            $errors = ValidationHelper::validateProject($name, $description);

            if (empty($errors)) {
                if ($this->projectModel->createProject($name, $description, $userId)) {
                    header('Location: /projects');
                    exit;
                } else {
                    $errors[] = "Failed to create project";
                }
            }
        }
        require_once __DIR__ . '/../Views/projects/create.php';
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