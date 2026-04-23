<?php
require_once 'app/core/Model.php';

class InvoiceModel extends Model {
    protected $table = 'invoices';

    public function getAllWithLead() {
        $sql = "SELECT i.*, l.lead_name, l.company_name 
                FROM invoices i 
                LEFT JOIN leads l ON i.lead_id = l.id 
                ORDER BY i.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getUnpaid() {
        $sql = "SELECT i.*, l.lead_name, l.company_name 
                FROM invoices i 
                LEFT JOIN leads l ON i.lead_id = l.id 
                WHERE i.status != 'Paid'
                ORDER BY i.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO invoices (invoice_no, lead_id, client_details, subtotal, discount, tax_percentage, vat_total, grand_total, due_date, payment_terms, status, created_by) 
                VALUES (:invoice_no, :lead_id, :client_details, :subtotal, :discount, :tax_percentage, :vat_total, :grand_total, :due_date, :payment_terms, :status, :created_by)";
        
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addItems($invoice_id, $items) {
        $sql = "INSERT INTO invoice_items (invoice_id, item_name, description, qty, unit_price, vat_percent, line_total) 
                VALUES (:invoice_id, :item_name, :description, :qty, :unit_price, :vat_percent, :line_total)";
        $stmt = $this->db->prepare($sql);

        foreach ($items as $item) {
            $item['invoice_id'] = $invoice_id;
            $stmt->execute($item);
        }
    }

    public function getItems($invoice_id) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :invoice_id");
        $stmt->execute(['invoice_id' => $invoice_id]);
        return $stmt->fetchAll();
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE invoices SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function update($id, $data) {
        $sql = "UPDATE invoices SET lead_id = :lead_id, client_details = :client_details, subtotal = :subtotal, discount = :discount, tax_percentage = :tax_percentage, vat_total = :vat_total, grand_total = :grand_total, due_date = :due_date, payment_terms = :payment_terms, status = :status WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function deleteItems($invoice_id) {
        $stmt = $this->db->prepare("DELETE FROM invoice_items WHERE invoice_id = :invoice_id");
        return $stmt->execute(['invoice_id' => $invoice_id]);
    }
}

