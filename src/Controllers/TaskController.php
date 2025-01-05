<?php

require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Tag.php';
require_once __DIR__ . '/../Helpers/AuthHelper.php';
require_once __DIR__ . '/../Helpers/ValidationHelper.php';

class TaskController {
    private $taskModel;
    private $categoryModel;
    private $tagModel;

    public function __construct() {
        $this->taskModel = new Task();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
    }

    public function create($projectId) {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $assignedTo = $_POST['assigned_to'] ?? null;
            $status = $_POST['status'] ?? 'pending';

            $errors = ValidationHelper::validateTask($title, $description);

            if (empty($errors)) {
                if ($this->taskModel->create($title, $description, $projectId, $assignedTo, $status)) {
                    $taskId = $this->taskModel->getLastInsertedId();

                    // Handle categories
                    if (isset($_POST['categories'])) {
                        foreach ($_POST['categories'] as $categoryId) {
                            $this->categoryModel->addTaskCategory($taskId, $categoryId);
                        }
                    }

                    // Handle tags
                    if (isset($_POST['tags'])) {
                        foreach ($_POST['tags'] as $tagId) {
                            $this->tagModel->addTaskTag($taskId, $tagId);
                        }
                    }

                    header('Location: /projects/view/' . $projectId);
                    exit;
                } else {
                    $errors[] = "Failed to create task";
                }
            }
        }

        $categories = $this->categoryModel->getAll();
        $tags = $this->tagModel->getAll();
        require_once __DIR__ . '/../Views/tasks/create.php';
    }

    public function edit($id) {
        AuthHelper::requireLogin();
        $task = $this->taskModel->getTaskById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $assignedTo = $_POST['assigned_to'] ?? null;
            $status = $_POST['status'] ?? 'pending';

            $errors = ValidationHelper::validateTask($title, $description);

            if (empty($errors)) {
                if ($this->taskModel->update($id, $title, $description, $assignedTo, $status)) {
                    // Update categories and tags
                    $this->categoryModel->removeTaskCategories($id);
                    $this->tagModel->removeTaskTags($id);

                    if (isset($_POST['categories'])) {
                        foreach ($_POST['categories'] as $categoryId) {
                            $this->categoryModel->addTaskCategory($id, $categoryId);
                        }
                    }

                    if (isset($_POST['tags'])) {
                        foreach ($_POST['tags'] as $tagId) {
                            $this->tagModel->addTaskTag($id, $tagId);
                        }
                    }

                    header('Location: /projects/view/' . $task['project_id']);
                    exit;
                } else {
                    $errors[] = "Failed to update task";
                }
            }
        }

        $categories = $this->categoryModel->getAll();
        $tags = $this->tagModel->getAll();
        $taskCategories = $this->categoryModel->getTaskCategories($id);
        $taskTags = $this->tagModel->getTaskTags($id);
        require_once __DIR__ . '/../Views/tasks/edit.php';
    }

    public function updateStatus($id, $status) {
        AuthHelper::requireLogin();
        if ($this->taskModel->updateStatus($id, $status)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update task status']);
        }
    }

    public function moveTask() {
        AuthHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'] ?? null;
            $newColumnId = $_POST['column_id'] ?? null;
            
            if ($taskId && $newColumnId) {
                $result = $this->taskModel->updateColumn($taskId, $newColumnId);
                echo json_encode(['success' => $result]);
                exit;
            }
        }
        
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
        exit;
    }
}