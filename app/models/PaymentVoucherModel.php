<?php
require_once 'app/core/Model.php';

class PaymentVoucherModel extends Model {
    protected $table = 'payment_vouchers';

    public function nextVoucherNo() {
        $year = date('Y');
        $stmt = $this->db->prepare(
            "SELECT voucher_no FROM payment_vouchers WHERE voucher_no LIKE :prefix ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute(['prefix' => "PV-$year-%"]);
        $last = $stmt->fetch();
        $n = $last ? ((int)substr($last['voucher_no'], -4) + 1) : 1;
        return "PV-$year-" . str_pad($n, 4, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO payment_vouchers
                    (voucher_no, lead_id, payment_date, payment_mode, amount, reference_number, description, notes, created_by)
                VALUES
                    (:voucher_no, :lead_id, :payment_date, :payment_mode, :amount, :reference_number, :description, :notes, :created_by)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function createItem($data) {
        $sql = "INSERT INTO payment_voucher_items (voucher_id, invoice_no, description, amount)
                VALUES (:voucher_id, :invoice_no, :description, :amount)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getItems($voucher_id) {
        $stmt = $this->db->prepare("SELECT * FROM payment_voucher_items WHERE voucher_id = :voucher_id ORDER BY id ASC");
        $stmt->execute(['voucher_id' => $voucher_id]);
        return $stmt->fetchAll();
    }

    public function findWithDetails($id) {
        $sql = "SELECT pv.*, l.lead_name, l.company_name, l.phone AS client_phone, l.email AS client_email, l.emirates
                FROM payment_vouchers pv
                LEFT JOIN leads l ON pv.lead_id = l.id
                WHERE pv.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAllWithDetails() {
        $sql = "SELECT pv.*, l.lead_name, l.company_name
                FROM payment_vouchers pv
                LEFT JOIN leads l ON pv.lead_id = l.id
                ORDER BY pv.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getInvoicesForLead($lead_id) {
        $sql = "SELECT
                    i.id,
                    i.invoice_no,
                    i.grand_total,
                    i.status,
                    i.due_date,
                    i.created_at,
                    COALESCE(SUM(r.amount_paid), 0)                        AS amount_received,
                    (i.grand_total - COALESCE(SUM(r.amount_paid), 0))      AS balance_due
                FROM invoices i
                LEFT JOIN receipts r ON r.invoice_id = i.id
                WHERE i.lead_id = :lead_id
                GROUP BY i.id, i.invoice_no, i.grand_total, i.status, i.due_date, i.created_at
                ORDER BY i.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lead_id' => $lead_id]);
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $sql = "UPDATE payment_vouchers SET
                    voucher_no = :voucher_no, lead_id = :lead_id, payment_date = :payment_date,
                    payment_mode = :payment_mode, amount = :amount, reference_number = :reference_number,
                    description = :description, notes = :notes
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteItems($voucher_id) {
        $stmt = $this->db->prepare("DELETE FROM payment_voucher_items WHERE voucher_id = :voucher_id");
        $stmt->execute(['voucher_id' => $voucher_id]);
    }

    public function getStatementForLead($lead_id) {
        $sql = "SELECT
                    pv.*,
                    GROUP_CONCAT(pvi.invoice_no ORDER BY pvi.id SEPARATOR ', ') AS invoices_covered
                FROM payment_vouchers pv
                LEFT JOIN payment_voucher_items pvi ON pvi.voucher_id = pv.id
                WHERE pv.lead_id = :lead_id
                GROUP BY pv.id
                ORDER BY pv.payment_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lead_id' => $lead_id]);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM payment_vouchers WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
