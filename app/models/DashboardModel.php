<?php
require_once 'app/core/Model.php';

class DashboardModel extends Model {
    
    public function getCounts() {
        $stats = [];
        
        // Total Leads
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM leads");
        $stats['total_leads'] = $stmt->fetch()['count'];

        // Won Leads
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM leads WHERE status = 'Won'");
        $stats['won_leads'] = $stmt->fetch()['count'];

        // Revenue Received (Actual Cash in Hand)
        $stmt = $this->db->query("
            SELECT 
                (SELECT COALESCE(SUM(amount_paid), 0) FROM receipts) +
                (SELECT COALESCE(SUM(grand_total), 0) FROM invoices WHERE status = 'Paid' AND id NOT IN (SELECT DISTINCT invoice_id FROM receipts)) 
            as total_revenue
        ");
        $total_received = $stmt->fetch()['total_revenue'];
        $stats['revenue'] = $total_received;

        // Total Invoiced (All Invoices)
        $stmt = $this->db->query("SELECT COALESCE(SUM(grand_total), 0) as total FROM invoices");
        $total_invoiced = $stmt->fetch()['total'];

        // Outstanding Balance (Total Invoiced - Total Received)
        $stats['outstanding_balance'] = $total_invoiced - $total_received;

        // Total Expenses
        $stmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM expenses");
        $stats['expenses'] = $stmt->fetch()['total'];

        // Expected Value (Pipeline)
        $stmt = $this->db->query("SELECT COALESCE(SUM(expected_value), 0) as total FROM leads WHERE status NOT IN ('Won', 'Lost')");
        $stats['expected_value'] = $stmt->fetch()['total'];

        return $stats;
    }

    public function getMonthlySales($months = 6) {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-$i months"));
            $monthEnd   = date('Y-m-t',  strtotime("-$i months"));
            $monthLabel = date('M',       strtotime("-$i months"));

            $stmt = $this->db->prepare("SELECT COALESCE(SUM(grand_total), 0) as total FROM invoices WHERE status = 'Paid' AND due_date BETWEEN :start AND :end");
            $stmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
            $revenue = $stmt->fetch()['total'];

            $data['labels'][]  = $monthLabel;
            $data['revenue'][] = $revenue;
        }
        return $data;
    }

    public function getLeadStatusDistribution() {
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM leads GROUP BY status");
        return $stmt->fetchAll();
    }

    public function getExpenseBreakdown() {
        $stmt = $this->db->query("SELECT category, COALESCE(SUM(amount), 0) as total FROM expenses GROUP BY category ORDER BY total DESC");
        return $stmt->fetchAll();
    }

    /**
     * Salesmen-wise report: leads, invoices, paid, unpaid, partial per salesman
     */
    public function getSalesmanReport() {
        $stmt = $this->db->query("
            SELECT
                u.id,
                u.name  AS salesman_name,
                u.role,
                COUNT(DISTINCT l.id)  AS total_leads,
                SUM(CASE WHEN l.status = 'Won' THEN 1 ELSE 0 END) AS won_leads,
                COUNT(DISTINCT i.id)  AS total_invoices,
                COALESCE(SUM(CASE WHEN i.status = 'Paid'    THEN i.grand_total ELSE 0 END), 0) AS paid_amount,
                COALESCE(SUM(CASE WHEN i.status = 'Unpaid'  THEN i.grand_total ELSE 0 END), 0) AS unpaid_amount,
                COALESCE(SUM(CASE WHEN i.status = 'Partial' THEN i.grand_total ELSE 0 END), 0) AS partial_amount,
                COALESCE(SUM(i.grand_total), 0) AS total_invoiced
            FROM users u
            LEFT JOIN leads    l ON l.sales_manager_id = u.id
            LEFT JOIN invoices i ON i.lead_id = l.id
            WHERE u.status = 'Active'
            GROUP BY u.id, u.name, u.role
            ORDER BY total_invoiced DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Invoice summary: total, paid, unpaid, partial counts and amounts
     */
    public function getInvoiceReport() {
        $stmt = $this->db->query("
            SELECT
                COUNT(*)  AS total_count,
                COALESCE(SUM(grand_total), 0) AS total_amount,
                SUM(CASE WHEN status = 'Paid'    THEN 1 ELSE 0 END) AS paid_count,
                COALESCE(SUM(CASE WHEN status = 'Paid'    THEN grand_total ELSE 0 END), 0) AS paid_amount,
                SUM(CASE WHEN status = 'Unpaid'  THEN 1 ELSE 0 END) AS unpaid_count,
                COALESCE(SUM(CASE WHEN status = 'Unpaid'  THEN grand_total ELSE 0 END), 0) AS unpaid_amount,
                SUM(CASE WHEN status = 'Partial' THEN 1 ELSE 0 END) AS partial_count,
                COALESCE(SUM(CASE WHEN status = 'Partial' THEN grand_total ELSE 0 END), 0) AS partial_amount
            FROM invoices
        ");
        return $stmt->fetch();
    }

    /**
     * Leads report by salesman with full status breakdown
     */
    public function getLeadsReport() {
        $stmt = $this->db->query("
            SELECT
                u.name AS salesman_name,
                COUNT(l.id) AS total_leads,
                SUM(CASE WHEN l.status = 'New'           THEN 1 ELSE 0 END) AS new_leads,
                SUM(CASE WHEN l.status = 'Contacted'     THEN 1 ELSE 0 END) AS contacted,
                SUM(CASE WHEN l.status = 'Qualified'     THEN 1 ELSE 0 END) AS qualified,
                SUM(CASE WHEN l.status = 'Proposal Sent' THEN 1 ELSE 0 END) AS proposal_sent,
                SUM(CASE WHEN l.status = 'Won'           THEN 1 ELSE 0 END) AS won,
                SUM(CASE WHEN l.status = 'Lost'          THEN 1 ELSE 0 END) AS lost,
                COALESCE(SUM(l.expected_value), 0) AS total_expected_value
            FROM users u
            LEFT JOIN leads l ON l.sales_manager_id = u.id
            WHERE u.status = 'Active'
            GROUP BY u.id, u.name
            ORDER BY total_leads DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Service/Item-wise invoice summary
     * Groups invoice_items by item_name with paid / unpaid / partial breakdown
     */
    public function getServiceItemReport() {
        $stmt = $this->db->query("
            SELECT
                ii.item_name                                                     AS service_name,
                COUNT(DISTINCT i.id)                                             AS total_invoices,
                COALESCE(SUM(ii.line_total), 0)                                  AS total_amount,

                -- Paid
                SUM(CASE WHEN i.status = 'Paid'    THEN 1 ELSE 0 END)           AS paid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Paid'    THEN ii.line_total ELSE 0 END), 0) AS paid_amount,

                -- Unpaid
                SUM(CASE WHEN i.status = 'Unpaid'  THEN 1 ELSE 0 END)           AS unpaid_count,
                COALESCE(SUM(CASE WHEN i.status = 'Unpaid'  THEN ii.line_total ELSE 0 END), 0) AS unpaid_amount,

                -- Partial
                SUM(CASE WHEN i.status = 'Partial' THEN 1 ELSE 0 END)           AS partial_count,
                COALESCE(SUM(CASE WHEN i.status = 'Partial' THEN ii.line_total ELSE 0 END), 0) AS partial_amount

            FROM invoice_items ii
            JOIN invoices i ON i.id = ii.invoice_id
            GROUP BY ii.item_name
            ORDER BY total_amount DESC
        ");
        return $stmt->fetchAll();
    }
}

