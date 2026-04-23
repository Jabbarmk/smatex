<?php
require_once 'app/core/Model.php';

class LeadModel extends Model {
    protected $table = 'leads';

    public function getAllWithSalesManager() {
        $sql = "SELECT l.*, u.name as sales_manager_name 
                FROM leads l 
                LEFT JOIN users u ON l.sales_manager_id = u.id 
                ORDER BY l.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO leads (lead_name, company_name, phone, email, sales_manager_id, emirates, status, expected_value, notes) 
                VALUES (:lead_name, :company_name, :phone, :email, :sales_manager_id, :emirates, :status, :expected_value, :notes)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE leads SET 
                lead_name = :lead_name, 
                company_name = :company_name, 
                phone = :phone, 
                email = :email, 
                sales_manager_id = :sales_manager_id, 
                emirates = :emirates, 
                status = :status, 
                expected_value = :expected_value, 
                notes = :notes
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
