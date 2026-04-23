<?php
require_once 'app/core/Model.php';

class SalesReportModel extends Model {

    /**
     * Summary of all salesmen: total paid, unpaid, partial invoices + amounts
     */
    public function getSalesmenSummary() {
        $stmt = $this->db->query("
            SELECT
                u.id                          AS salesman_id,
                u.name                        AS salesman_name,
                u.email                       AS salesman_email,
                u.phone                       AS salesman_phone,
                u.role,

                -- Leads
                COUNT(DISTINCT l.id)          AS total_leads,
                SUM(CASE WHEN l.status = 'Won'  THEN 1 ELSE 0 END) AS won_leads,
                SUM(CASE WHEN l.status = 'Lost' THEN 1 ELSE 0 END) AS lost_leads,

                -- Invoices
                COUNT(DISTINCT i.id)          AS total_invoices,

                -- Paid
                SUM(CASE WHEN i.status = 'Paid'    THEN 1 ELSE 0 END) AS paid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Paid'    THEN i.grand_total ELSE 0 END), 0) AS paid_amount,

                -- Unpaid
                SUM(CASE WHEN i.status = 'Unpaid'  THEN 1 ELSE 0 END) AS unpaid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Unpaid'  THEN i.grand_total ELSE 0 END), 0) AS unpaid_amount,

                -- Partial
                SUM(CASE WHEN i.status = 'Partial' THEN 1 ELSE 0 END) AS partial_count,
                COALESCE(SUM(CASE WHEN i.status = 'Partial' THEN i.grand_total ELSE 0 END), 0) AS partial_amount,

                -- Grand Total
                COALESCE(SUM(i.grand_total), 0) AS total_invoiced

            FROM users u
            LEFT JOIN leads    l ON l.sales_manager_id = u.id
            LEFT JOIN invoices i ON i.lead_id = l.id
            WHERE u.status = 'Active'
            GROUP BY u.id, u.name, u.email, u.phone, u.role
            ORDER BY total_invoiced DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Grand totals row for the summary footer
     */
    public function getGrandTotals() {
        $stmt = $this->db->query("
            SELECT
                COUNT(DISTINCT i.id)         AS total_invoices,
                SUM(CASE WHEN i.status = 'Paid'    THEN 1 ELSE 0 END) AS paid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Paid'    THEN i.grand_total ELSE 0 END), 0) AS paid_amount,
                SUM(CASE WHEN i.status = 'Unpaid'  THEN 1 ELSE 0 END) AS unpaid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Unpaid'  THEN i.grand_total ELSE 0 END), 0) AS unpaid_amount,
                SUM(CASE WHEN i.status = 'Partial' THEN 1 ELSE 0 END) AS partial_count,
                COALESCE(SUM(CASE WHEN i.status = 'Partial' THEN i.grand_total ELSE 0 END), 0) AS partial_amount,
                COALESCE(SUM(i.grand_total), 0) AS total_invoiced
            FROM invoices i
        ");
        return $stmt->fetch();
    }

    /**
     * Salesman info
     */
    public function getSalesmanById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Detail view: all clients (leads) with their invoices for a salesman
     */
    public function getSalesmanDetail($salesman_id) {
        $stmt = $this->db->prepare("
            SELECT
                l.id                           AS lead_id,
                l.lead_name                    AS client_name,
                l.company_name,
                l.phone                        AS client_phone,
                l.email                        AS client_email,
                l.emirates,
                l.status                       AS lead_status,

                -- Per-lead invoice summary
                COUNT(DISTINCT i.id)           AS invoice_count,
                SUM(CASE WHEN i.status = 'Paid'    THEN 1 ELSE 0 END) AS paid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Paid'    THEN i.grand_total ELSE 0 END), 0) AS paid_amount,
                SUM(CASE WHEN i.status = 'Unpaid'  THEN 1 ELSE 0 END) AS unpaid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Unpaid'  THEN i.grand_total ELSE 0 END), 0) AS unpaid_amount,
                SUM(CASE WHEN i.status = 'Partial' THEN 1 ELSE 0 END) AS partial_count,
                COALESCE(SUM(CASE WHEN i.status = 'Partial' THEN i.grand_total ELSE 0 END), 0) AS partial_amount,
                COALESCE(SUM(i.grand_total), 0) AS total_invoiced

            FROM leads l
            LEFT JOIN invoices i ON i.lead_id = l.id
            WHERE l.sales_manager_id = :salesman_id
            GROUP BY l.id, l.lead_name, l.company_name, l.phone, l.email, l.emirates, l.status
            ORDER BY total_invoiced DESC
        ");
        $stmt->execute(['salesman_id' => $salesman_id]);
        return $stmt->fetchAll();
    }

    /**
     * Individual invoices for a specific client (lead) under a salesman
     */
    public function getInvoicesForLead($lead_id) {
        $stmt = $this->db->prepare("
            SELECT
                i.id,
                i.invoice_no,
                i.grand_total,
                i.status,
                i.due_date,
                i.created_at,
                COALESCE(SUM(r.amount_paid), 0) AS amount_received
            FROM invoices i
            LEFT JOIN receipts r ON r.invoice_id = i.id
            WHERE i.lead_id = :lead_id
            GROUP BY i.id, i.invoice_no, i.grand_total, i.status, i.due_date, i.created_at
            ORDER BY i.created_at DESC
        ");
        $stmt->execute(['lead_id' => $lead_id]);
        return $stmt->fetchAll();
    }

    /**
     * Complete flat list of all invoices for a salesman (used for Excel export)
     */
    public function getSalesmanInvoicesFlat($salesman_id) {
        $stmt = $this->db->prepare("
            SELECT
                l.lead_name AS client_name,
                l.company_name,
                l.phone     AS client_phone,
                i.invoice_no,
                i.grand_total,
                i.status    AS invoice_status,
                i.due_date,
                COALESCE(SUM(r.amount_paid), 0) AS amount_received,
                (i.grand_total - COALESCE(SUM(r.amount_paid), 0)) AS balance_due,
                i.created_at
            FROM leads l
            JOIN invoices i ON i.lead_id = l.id
            LEFT JOIN receipts r ON r.invoice_id = i.id
            WHERE l.sales_manager_id = :salesman_id
            GROUP BY i.id, l.lead_name, l.company_name, l.phone, i.invoice_no,
                     i.grand_total, i.status, i.due_date, i.created_at
            ORDER BY l.lead_name, i.created_at DESC
        ");
        $stmt->execute(['salesman_id' => $salesman_id]);
        return $stmt->fetchAll();
    }
}
