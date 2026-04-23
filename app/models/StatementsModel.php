<?php
require_once 'app/core/Model.php';

class StatementsModel extends Model {

    /** All active salesmen for the dropdown */
    public function getAllSalesmen() {
        return $this->db->query("
            SELECT id, name, email, role
            FROM users
            WHERE status = 'Active'
            ORDER BY name
        ")->fetchAll();
    }

    /** All clients (leads) for the dropdown — show company + lead name */
    public function getAllClients() {
        return $this->db->query("
            SELECT
                l.id,
                l.lead_name,
                l.company_name,
                l.phone,
                l.email,
                u.name AS salesman_name
            FROM leads l
            LEFT JOIN users u ON u.id = l.sales_manager_id
            ORDER BY l.company_name, l.lead_name
        ")->fetchAll();
    }

    /** Salesman info */
    public function getSalesmanById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /** Client (lead) info */
    public function getClientById($id) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.name AS salesman_name
            FROM leads l
            LEFT JOIN users u ON u.id = l.sales_manager_id
            WHERE l.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Sales Statement: all invoices for a salesman
     * Returns: lead_name, company_name, invoice_no, grand_total, status, due_date, created_at, amount_received
     */
    public function getSalesStatement($salesman_id) {
        $stmt = $this->db->prepare("
            SELECT
                l.lead_name,
                l.company_name,
                l.emirates,
                i.id            AS invoice_id,
                i.invoice_no,
                i.grand_total,
                i.status        AS invoice_status,
                i.due_date,
                i.created_at,
                COALESCE(SUM(r.amount_paid), 0) AS amount_received
            FROM leads l
            JOIN invoices i ON i.lead_id = l.id
            LEFT JOIN receipts r ON r.invoice_id = i.id
            WHERE l.sales_manager_id = :salesman_id
            GROUP BY i.id, l.lead_name, l.company_name, l.emirates,
                     i.invoice_no, i.grand_total, i.status, i.due_date, i.created_at
            ORDER BY i.created_at ASC
        ");
        $stmt->execute(['salesman_id' => $salesman_id]);
        return $stmt->fetchAll();
    }

    /**
     * Client Statement: all invoices for a specific lead/client
     * Returns: invoice_no, grand_total, status, due_date, created_at, amount_received
     */
    public function getClientStatement($lead_id) {
        $stmt = $this->db->prepare("
            SELECT
                i.id            AS invoice_id,
                i.invoice_no,
                i.grand_total,
                i.status        AS invoice_status,
                i.due_date,
                i.created_at,
                COALESCE(SUM(r.amount_paid), 0) AS amount_received,
                (i.grand_total - COALESCE(SUM(r.amount_paid), 0)) AS balance_due
            FROM invoices i
            LEFT JOIN receipts r ON r.invoice_id = i.id
            WHERE i.lead_id = :lead_id
            GROUP BY i.id, i.invoice_no, i.grand_total, i.status, i.due_date, i.created_at
            ORDER BY i.created_at ASC
        ");
        $stmt->execute(['lead_id' => $lead_id]);
        return $stmt->fetchAll();
    }
}
