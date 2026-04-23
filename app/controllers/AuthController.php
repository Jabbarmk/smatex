<?php
class AuthController extends Controller {

    public function index() {
        $this->login();
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        $this->view('auth/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF Token Check should go here
            
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            require_once 'app/models/UserModel.php';
            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'Active') {
                    $error = "Account inactive. Contact admin.";
                    $this->view('auth/login', ['error' => $error]);
                    return;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                
                $this->redirect('dashboard');
            } else {
                $error = "Invalid credentials";
                $this->view('auth/login', ['error' => $error]);
            }
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
