<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        require_once "app/views/layout/header.php"; // We will create this later
        require_once "app/views/" . $view . ".php";
        require_once "app/views/layout/footer.php"; // We will create this later
    }

    public function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    // Middleware check
    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    protected function requireRole($roles = []) {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
            // Redirect to unauthorized or show error
            die('Unauthorized Access');
        }
    }
}
