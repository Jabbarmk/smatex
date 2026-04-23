<?php
class Model {
    protected $db;
    protected $table = '';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM " . $this->table);
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM " . $this->table);
        $res = $stmt->fetch();
        return $res ? $res['count'] : 0;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
