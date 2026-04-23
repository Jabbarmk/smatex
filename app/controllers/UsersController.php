<?php
class UsersController extends Controller {
    public function __construct() {
        $this->requireLogin();
        // Permitted Roles: Only Super Admin per requirements ("User Master: Full CRUD - Super Admin only")
        $this->requireRole(['Super Admin']);
    }

    public function index() {
        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $users = $userModel->getAll();
        
        $this->view('users/index', ['users' => $users, 'title' => 'User Management']);
    }

    public function create() {
        $this->view('users/create', ['title' => 'Create New User']);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/UserModel.php';
            $userModel = new UserModel();

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $password,
                'phone' => $_POST['phone'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            try {
                if ($userModel->create($data)) {
                    $this->redirect('users');
                } else {
                    echo "Error creating user";
                }
            } catch (PDOException $e) {
               if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                   echo "Email already exists!";
               } else {
                   echo "Database Error: " . $e->getMessage();
               }
            }
        }
    }

    public function edit($id) {
        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            $this->redirect('users');
        }

        $this->view('users/edit', ['user' => $user, 'title' => 'Edit User']);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/UserModel.php';
            $userModel = new UserModel();

            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            if ($userModel->update($id, $data)) {
                // Handle Password Reset if provided
                if (!empty($_POST['password'])) {
                    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $userModel->updatePassword($id, $newPassword);
                }
                $this->redirect('users');
            } else {
                echo "Error updating user";
            }
        }
    }

    public function delete($id) {
        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            die("Cannot delete logged in user");
        }

        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $userModel->delete($id);
        $this->redirect('users');
    }
}
