<?php
require_once 'app/core/Model.php';

class ExpenseModel extends Model {
    protected $table = 'expenses';

    public function getAllWithCreator() {
        $sql = "SELECT e.*, u.name as created_by_name 
                FROM expenses e 
                LEFT JOIN users u ON e.created_by = u.id 
                ORDER BY e.expense_date DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO expenses (title, category, amount, expense_date, payment_mode, description, created_by) 
                VALUES (:title, :category, :amount, :expense_date, :payment_mode, :description, :created_by)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE expenses SET 
                title = :title, 
                category = :category, 
                amount = :amount, 
                expense_date = :expense_date, 
                payment_mode = :payment_mode, 
                description = :description
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function filter($month = null, $date = null) {
        $sql = "SELECT e.*, u.name as created_by_name 
                FROM expenses e 
                LEFT JOIN users u ON e.created_by = u.id 
                WHERE 1=1";
        
        $params = [];

        if (!empty($date)) {
            $sql .= " AND e.expense_date = :date";
            $params['date'] = $date;
        } elseif (!empty($month)) {
            $sql .= " AND DATE_FORMAT(e.expense_date, '%Y-%m') = :month";
            $params['month'] = $month;
        }

        $sql .= " ORDER BY e.expense_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
