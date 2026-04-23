<?php
require_once 'app/core/Model.php';

class UserModel extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(); // Returns false if not found
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, phone, role, status) VALUES (:name, :email, :password, :phone, :role, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, role = :role, status = :status WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updatePassword($id, $password) {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['id' => $id, 'password' => $password]);
    }
}
