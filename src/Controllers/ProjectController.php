<?php

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';

class ProjectController {
    private $projectModel;

    public function __construct() {
        $this->projectModel = new Project();
    }

    public function index() {
        AuthHelper::requireLogin();
        $userId = $_SESSION['user_id'];
        $projects = $this->projectModel->getProjectsByUserId($userId);
        require_once __DIR__ . '/../Views/projects/index.php';
    }  public function create() {
        AuthHelper::requireLogin();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;
            $userId = $_SESSION['user_id'];

            error_log("Attempting to create project: " . json_encode($_POST));

            $errors = ValidationHelper::validateProject($name, $description);

            if (empty($errors)) {
                try {
                    if ($this->projectModel->createProject($name, $description, $isPublic, $userId)) {
                        error_log("Project created successfully");
                        header('Location: /projects');
                        exit;
                    } else {
                        error_log("Failed to create project");
                        $errors[] = "Failed to create project";
                    }
                } catch (Exception $e) {
                    error_log("Exception when creating project: " . $e->getMessage());
                    $errors[] = "An error occurred while creating the project";
                }
            } else {
                error_log("Validation errors: " . json_encode($errors));
            }
        }

        require_once __DIR__ . '/../Views/projects/create.php';
    }
    // public function create() {
    //     try {
    //         // Ensure user is logged in
    //         AuthMiddleware::requireLogin();
    
    //         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //             $name = $_POST['name'];
    //             $description = $_POST['description'];
    //             $userId = $_SESSION['user_id']; // Logged-in user ID
    //             $isPublic = isset($_POST['is_public']) ? $_POST['is_public'] : false;
    
    //             // Ensure valid data
    //             if (empty($name) || empty($description)) {
    //                 throw new Exception("Project name and description are required.");
    //             }
    
    //             $project = new Project();
    //             $projectId = $project->createProject($name, $description, $userId, $isPublic);
    
    //             // Redirect after project creation
    //             header("Location: /projects/{$projectId}");
    //             exit;
    //         } else {
    //             // If GET request, display the create project form
    //             require_once __DIR__ . '/../Views/projects/create.php';
    //         }
    //     } catch (Exception $e) {
    //         echo "Error: " . $e->getMessage();
    //     }
    // }
    
    public function view($id) {
        AuthHelper::requireLogin();
        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            header('Location: /projects');
            exit;
        }
        require_once __DIR__ . '/../Views/projects/view.php';
    }

    public function edit($id) {
        AuthHelper::requireLogin();
        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            header('Location: /projects');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;

            $errors = ValidationHelper::validateProject($name, $description);

            if (empty($errors)) {
                if ($this->projectModel->updateProject($id, $name, $description, $isPublic)) {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->projectModel->deleteProject($id)) {
                header('Location: /projects');
                exit;
            } else {
                $error = "Failed to delete project";
            }
        }
        $project = $this->projectModel->getProjectById($id);
        require_once __DIR__ . '/../Views/projects/delete.php';
    }
}