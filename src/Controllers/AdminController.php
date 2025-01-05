<?php

namespace App\Controllers;
namespace App\Models; 
use App\Models\User;
use App\Models\Project;
use App\Models\Role;
use App\Middleware\AuthMiddleware;

class AdminController {
    public function __construct() {
        AuthMiddleware::requireAdmin();
    }

    public function index() {
        $stats = [
            'totalUsers' => User::count(),
            'totalProjects' => Project::count(),
            'totalRoles' => Role::count(),
        ];

        require_once __DIR__ . '/../Views/admin/admin_dashboard.php';
    }

    public function users() {
        $users = User::getAll();
        $roles = Role::getAll();

        require_once __DIR__ . '/../Views/admin/users.php';
    }

    public function editUser($id) {
        $user = User::findById($id);
        if (!$user) {
            header('Location: /admin/users?error=User not found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $roleId = $_POST['role_id'] ?? '';

            $user->setName($name);
            $user->setEmail($email);
            $user->setRoleId($roleId);
            $user->update();

            header('Location: /admin/users?message=User updated successfully');
            exit;
        }

        $roles = Role::getAll();
        require_once __DIR__ . '/../Views/admin/edit_user.php';
    }

    public function deleteUser($id) {
        $user = User::findById($id);
        if (!$user) {
            header('Location: /admin/users?error=User not found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            User::delete($id);
            header('Location: /admin/users?message=User deleted successfully');
            exit;
        }

        require_once __DIR__ . '/../Views/admin/delete_user.php';
    }

    public function projects() {
        $projects = Project::getAll();
        require_once __DIR__ . '/../Views/admin/projects.php';
    }

    public function editProject($id) {
        $project = Project::findById($id);
        if (!$project) {
            header('Location: /admin/projects?error=Project not found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $isPublic = isset($_POST['is_public']) ? 1 : 0;

            $project->setName($name);
            $project->setDescription($description);
            $project->setIsPublic($isPublic);
            $project->update();

            header('Location: /admin/projects?message=Project updated successfully');
            exit;
        }

        require_once __DIR__ . '/../Views/admin/edit_project.php';
    }

    public function deleteProject($id) {
        $project = Project::findById($id);
        if (!$project) {
            header('Location: /admin/projects?error=Project not found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Project::delete($id);
            header('Location: /admin/projects?message=Project deleted successfully');
            exit;
        }

        require_once __DIR__ . '/../Views/admin/delete_project.php';
    }
}