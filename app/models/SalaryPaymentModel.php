<?php
require_once 'app/core/Model.php';

class SalaryPaymentModel extends Model {
    protected $table = 'salary_payments';

    public function getAllWithEmployee() {
        $sql = "SELECT sp.*, e.full_name, e.designation, e.department, e.employee_no
                FROM salary_payments sp
                JOIN employees e ON sp.employee_id = e.id
                ORDER BY sp.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findWithEmployee($id) {
        $sql = "SELECT sp.*, e.full_name, e.designation, e.department, e.employee_no,
                       e.bank_name, e.bank_account, e.iban, e.nationality, e.passport_no
                FROM salary_payments sp
                JOIN employees e ON sp.employee_id = e.id
                WHERE sp.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO salary_payments (voucher_no, employee_id, payment_month, payment_year, payment_date, basic_salary, housing_allowance, transport_allowance, other_allowance, gross_salary, deductions, deduction_reason, net_salary, payment_mode, notes, created_by)
                VALUES (:voucher_no, :employee_id, :payment_month, :payment_year, :payment_date, :basic_salary, :housing_allowance, :transport_allowance, :other_allowance, :gross_salary, :deductions, :deduction_reason, :net_salary, :payment_mode, :notes, :created_by)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) return $this->db->lastInsertId();
        return false;
    }

    public function nextVoucherNo() {
        $row = $this->db->query("SELECT MAX(CAST(SUBSTRING(voucher_no,5) AS UNSIGNED)) as maxn FROM salary_payments WHERE voucher_no LIKE 'SAL-'")->fetch();
        // Use count-based approach instead
        $count = $this->db->query("SELECT COUNT(*) as c FROM salary_payments")->fetch()['c'] ?? 0;
        return 'SAL-' . date('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getByEmployee($employee_id) {
        $stmt = $this->db->prepare("SELECT * FROM salary_payments WHERE employee_id = :eid ORDER BY payment_year DESC, FIELD(payment_month,'January','February','March','April','May','June','July','August','September','October','November','December') DESC");
        $stmt->execute(['eid' => $employee_id]);
        return $stmt->fetchAll();
    }
}
