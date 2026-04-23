<?php
require_once 'app/core/Model.php';

class EmployeeModel extends Model {
    protected $table = 'employees';

    public function getAll() {
        return $this->db->query("SELECT * FROM employees ORDER BY full_name ASC")->fetchAll();
    }

    public function getActive() {
        return $this->db->query("SELECT * FROM employees WHERE status = 'Active' ORDER BY full_name ASC")->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO employees (employee_no, full_name, designation, department, nationality, passport_no, visa_uid, emirates_id, mobile, email, bank_name, bank_account, iban, basic_salary, housing_allowance, transport_allowance, other_allowance, join_date, status, notes)
                VALUES (:employee_no, :full_name, :designation, :department, :nationality, :passport_no, :visa_uid, :emirates_id, :mobile, :email, :bank_name, :bank_account, :iban, :basic_salary, :housing_allowance, :transport_allowance, :other_allowance, :join_date, :status, :notes)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE employees SET full_name=:full_name, designation=:designation, department=:department, nationality=:nationality, passport_no=:passport_no, visa_uid=:visa_uid, emirates_id=:emirates_id, mobile=:mobile, email=:email, bank_name=:bank_name, bank_account=:bank_account, iban=:iban, basic_salary=:basic_salary, housing_allowance=:housing_allowance, transport_allowance=:transport_allowance, other_allowance=:other_allowance, join_date=:join_date, status=:status, notes=:notes WHERE id=:id";
        $data['id'] = $id;
        return $this->db->prepare($sql)->execute($data);
    }

    public function nextEmployeeNo() {
        $row = $this->db->query("SELECT MAX(CAST(SUBSTRING(employee_no,4) AS UNSIGNED)) as maxn FROM employees WHERE employee_no LIKE 'EMP%'")->fetch();
        $next = ($row['maxn'] ?? 0) + 1;
        return 'EMP' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
