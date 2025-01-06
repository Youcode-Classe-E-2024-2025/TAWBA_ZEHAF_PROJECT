<?php

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';

class ProjectController {
    private $projectModel;
    private $taskModel;

    public function __construct() {
        $this->projectModel = new Project();
        $this->taskModel = new Task();
    }

    public function index() {
        AuthHelper::requireLogin();
        $userId = $_SESSION['user_id'];
        $projects = $this->projectModel->getProjectsByUserId($userId);
        require_once __DIR__ . '/../Views/projects/index.php';
    }

    public function create() {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;
            $userId = $_SESSION['user_id'];

            $errors = ValidationHelper::validateProject($name, $description);

            if (empty($errors)) {
                if ($this->projectModel->create($name, $description, $userId, $isPublic)) {
                    header('Location: /projects');
                    exit;
                } else {
                    $errors[] = "Failed to create project";
                }
            }
        }
        require_once __DIR__ . '/../Views/projects/create.php';
    }

    public function edit($id) {
        AuthHelper::requireLogin();
        $project = $this->projectModel->getProjectById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;

            $errors = ValidationHelper::validateProject($name, $description);

            if (empty($errors)) {
                if ($this->projectModel->update($id, $name, $description, $isPublic)) {
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
        AuthHelper::requireLogin();
        if ($this->projectModel->delete($id)) {
            header('Location: /projects');
            exit;
        } else {
            $error = "Failed to delete project";
            require_once __DIR__ . '/../Views/projects/index.php';
        }
    }

    public function view($id) {
        AuthHelper::requireLogin();
        $project = $this->projectModel->getProjectById($id);
        $tasks = $this->taskModel->getTasksByProjectId($id);
        require_once __DIR__ . '/../Views/projects/view.php';
    }
}