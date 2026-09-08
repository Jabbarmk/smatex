<?php
require_once 'app/core/Model.php';

class InvoiceModel extends Model {
    protected $table = 'invoices';

    public function getAllWithLead() {
        $sql = "SELECT i.*, l.lead_name, l.company_name
                FROM invoices i
                LEFT JOIN leads l ON i.lead_id = l.id
                WHERE i.is_draft = 0
                ORDER BY i.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllWithLeadReal() {
        $sql = "SELECT i.*, l.lead_name, l.company_name
                FROM invoices i
                LEFT JOIN leads l ON i.lead_id = l.id
                WHERE i.is_draft = 0
                ORDER BY i.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllWithLeadDraft() {
        $sql = "SELECT i.*, l.lead_name, l.company_name
                FROM invoices i
                LEFT JOIN leads l ON i.lead_id = l.id
                WHERE i.is_draft = 1
                ORDER BY i.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getUnpaid() {
        $sql = "SELECT i.*, l.lead_name, l.company_name
                FROM invoices i
                LEFT JOIN leads l ON i.lead_id = l.id
                WHERE i.status != 'Paid' AND i.is_draft = 0
                ORDER BY i.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getNextInvoiceNo() {
        $year = date('Y');
        $stmt = $this->db->prepare(
            "SELECT invoice_no FROM invoices WHERE invoice_no LIKE :pattern AND is_draft = 0 ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute(['pattern' => 'INV-' . $year . '-%']);
        $last = $stmt->fetchColumn();
        $num = $last ? (intval(substr($last, strrpos($last, '-') + 1)) + 1) : 1;
        return 'INV-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function getNextDraftNo() {
        $year = date('Y');
        $stmt = $this->db->prepare(
            "SELECT invoice_no FROM invoices WHERE invoice_no LIKE :pattern AND is_draft = 1 ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute(['pattern' => 'DFT-' . $year . '-%']);
        $last = $stmt->fetchColumn();
        $num = $last ? (intval(substr($last, strrpos($last, '-') + 1)) + 1) : 1;
        return 'DFT-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function getSummaryStats() {
        $month = date('Y-m');
        $stats = [];

        $stmt = $this->db->query("SELECT COALESCE(SUM(grand_total),0) FROM invoices WHERE is_draft=0");
        $stats['total_all_time'] = (float)$stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT COALESCE(SUM(grand_total),0) FROM invoices WHERE is_draft=0 AND DATE_FORMAT(created_at,'%Y-%m')=:m");
        $stmt->execute(['m' => $month]);
        $stats['this_month'] = (float)$stmt->fetchColumn();

        foreach (['Paid', 'Unpaid', 'Partial'] as $s) {
            $stmt = $this->db->prepare("SELECT COUNT(*), COALESCE(SUM(grand_total),0) FROM invoices WHERE is_draft=0 AND status=:s");
            $stmt->execute(['s' => $s]);
            $row = $stmt->fetch(PDO::FETCH_NUM);
            $key = strtolower($s);
            $stats['count_' . $key]  = (int)$row[0];
            $stats['amount_' . $key] = (float)$row[1];
        }

        $stmt = $this->db->query("
            SELECT COALESCE(SUM(i.grand_total),0) - COALESCE(SUM(r.paid),0)
            FROM invoices i
            LEFT JOIN (
                SELECT invoice_id, SUM(amount_paid) AS paid FROM receipts GROUP BY invoice_id
            ) r ON r.invoice_id = i.id
            WHERE i.is_draft=0 AND i.status != 'Paid'
        ");
        $stats['outstanding'] = (float)$stmt->fetchColumn();

        return $stats;
    }

    public function getUniqueClients() {
        $sql = "SELECT DISTINCT l.lead_name, l.company_name
                FROM invoices i
                LEFT JOIN leads l ON i.lead_id = l.id
                WHERE i.is_draft = 0 AND l.lead_name IS NOT NULL
                ORDER BY l.lead_name";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO invoices (invoice_no, lead_id, quotation_id, client_details, subtotal, discount, tax_percentage, vat_total, grand_total, due_date, payment_terms, status, is_draft, created_by)
                VALUES (:invoice_no, :lead_id, :quotation_id, :client_details, :subtotal, :discount, :tax_percentage, :vat_total, :grand_total, :due_date, :payment_terms, :status, :is_draft, :created_by)";
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
        $data['id'] = $id;
        $sql = "UPDATE invoices SET lead_id=:lead_id, client_details=:client_details, subtotal=:subtotal, discount=:discount, tax_percentage=:tax_percentage, vat_total=:vat_total, grand_total=:grand_total, due_date=:due_date, payment_terms=:payment_terms, status=:status";
        if (isset($data['invoice_no'])) {
            $sql .= ", invoice_no=:invoice_no";
        }
        if (isset($data['created_at'])) {
            $sql .= ", created_at=:created_at";
        }
        $sql .= " WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function promote($id, $newInvoiceNo) {
        $stmt = $this->db->prepare("UPDATE invoices SET is_draft=0, invoice_no=:no WHERE id=:id");
        return $stmt->execute(['no' => $newInvoiceNo, 'id' => $id]);
    }

    public function deleteItems($invoice_id) {
        $stmt = $this->db->prepare("DELETE FROM invoice_items WHERE invoice_id = :invoice_id");
        return $stmt->execute(['invoice_id' => $invoice_id]);
    }
}
