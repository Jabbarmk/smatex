<?php
require_once 'app/core/Model.php';

class ReceiptModel extends Model {
    protected $table = 'receipts';

    public function create($data) {
        $sql = "INSERT INTO receipts (receipt_no, invoice_id, payment_date, amount_paid, payment_mode, reference_number, notes, created_by) 
                VALUES (:receipt_no, :invoice_id, :payment_date, :amount_paid, :payment_mode, :reference_number, :notes, :created_by)";
        
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getByInvoiceId($invoice_id) {
        $stmt = $this->db->prepare("SELECT * FROM receipts WHERE invoice_id = :invoice_id ORDER BY payment_date DESC");
        $stmt->execute(['invoice_id' => $invoice_id]);
        return $stmt->fetchAll();
    }
    
    public function getTotalPaid($invoice_id) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount_paid), 0) as total FROM receipts WHERE invoice_id = :invoice_id");
        $stmt->execute(['invoice_id' => $invoice_id]);
        return $stmt->fetch()['total'];
    }
    public function getAllWithInvoice() {
        $sql = "SELECT r.*, i.invoice_no, i.client_details, l.lead_name 
                FROM receipts r 
                LEFT JOIN invoices i ON r.invoice_id = i.id 
                LEFT JOIN leads l ON i.lead_id = l.id 
                ORDER BY r.payment_date DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM receipts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $sql = "UPDATE receipts SET 
                payment_date = :payment_date, 
                amount_paid = :amount_paid, 
                payment_mode = :payment_mode, 
                reference_number = :reference_number, 
                notes = :notes 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function filter($month = null, $date = null) {
        $sql = "SELECT r.*, i.invoice_no, i.client_details, l.lead_name 
                FROM receipts r 
                LEFT JOIN invoices i ON r.invoice_id = i.id 
                LEFT JOIN leads l ON i.lead_id = l.id 
                WHERE 1=1";
        
        $params = [];

        if (!empty($date)) {
            $sql .= " AND r.payment_date = :date";
            $params['date'] = $date;
        } elseif (!empty($month)) {
            $sql .= " AND DATE_FORMAT(r.payment_date, '%Y-%m') = :month";
            $params['month'] = $month;
        }

        $sql .= " ORDER BY r.payment_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM receipts WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
