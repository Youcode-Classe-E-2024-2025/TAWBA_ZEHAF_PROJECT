<?php

class ProjectController {
    private $projectModel;
    private $taskModel;

    public function __construct() {
        $this->projectModel = new Project();
        $this->taskModel = new Task();
    }

    public function index() {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $projects = $this->projectModel->getProjectsByUserId($userId);
        } else {
            $projects = $this->projectModel->getPublicProjects();
        }
        require_once __DIR__ . '/../Views/projects/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;
            $userId = $_SESSION['user_id'];

            if ($this->projectModel->create($name, $description, $userId, $isPublic)) {
                header('Location: /projects');
                exit;
            } else {
                $error = "Failed to create project";
            }
        }
        require_once __DIR__ . '/../Views/projects/create.php';
    }

    public function edit($id) {
        $project = $this->projectModel->getProjectById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;

            if ($this->projectModel->update($id, $name, $description, $isPublic)) {
                header('Location: /projects');
                exit;
            } else {
                $error = "Failed to update project";
            }
        }
        require_once __DIR__ . '/../Views/projects/edit.php';
    }

    public function delete($id) {
        if ($this->projectModel->delete($id)) {
            header('Location: /projects');
            exit;
        } else {
            $error = "Failed to delete project";
            require_once __DIR__ . '/../Views/projects/index.php';
        }
    }

    public function view($id) {
        $project = $this->projectModel->getProjectById($id);
        $tasks = $this->taskModel->getTasksByProjectId($id);
        require_once __DIR__ . '/../Views/projects/view.php';
    }
}