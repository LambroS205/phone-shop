<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;
use App\Core\Security;

class AuthController {
    private User $userModel;

    public function __construct() { $this->userModel = new User(); }

    public function showLogin() {
        require BASE_PATH . '/app/Views/auth/login.php';
    }

    public function login() {
        // Verify CSRF
        if (!Security::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF token");
        }

        $user = $this->userModel->findByEmail($_POST['email']);
        if ($user && $this->userModel->verifyPassword($user['password'], $_POST['password'])) {
            Session::regenerate();
            Session::put('user_id', $user['id']);
            Session::put('user_role', $user['role']);
            Session::put('user_name', $user['name']);
            header('Location: /');
            exit;
        }
        echo "Invalid credentials";
    }

    public function logout() {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
