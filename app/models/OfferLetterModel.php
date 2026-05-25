<?php
require_once 'app/core/Model.php';

class OfferLetterModel extends Model {
    protected $table = 'offer_letters';

    public function getAll() {
        return $this->db->query("SELECT * FROM offer_letters ORDER BY created_at DESC")->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO offer_letters
            (offer_no, employee_id, candidate_name, designation, department, nationality,
             offer_date, joining_date, basic_salary, housing_allowance, transport_allowance,
             other_allowance, probation_period, working_hours, annual_leave, notice_period,
             issued_by, issued_by_title, notes, created_by)
            VALUES
            (:offer_no, :employee_id, :candidate_name, :designation, :department, :nationality,
             :offer_date, :joining_date, :basic_salary, :housing_allowance, :transport_allowance,
             :other_allowance, :probation_period, :working_hours, :annual_leave, :notice_period,
             :issued_by, :issued_by_title, :notes, :created_by)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE offer_letters SET
            candidate_name=:candidate_name, designation=:designation, department=:department,
            nationality=:nationality, offer_date=:offer_date, joining_date=:joining_date,
            basic_salary=:basic_salary, housing_allowance=:housing_allowance,
            transport_allowance=:transport_allowance, other_allowance=:other_allowance,
            probation_period=:probation_period, working_hours=:working_hours,
            annual_leave=:annual_leave, notice_period=:notice_period,
            issued_by=:issued_by, issued_by_title=:issued_by_title, notes=:notes
            WHERE id=:id";
        $data['id'] = $id;
        return $this->db->prepare($sql)->execute($data);
    }

    public function nextOfferNo() {
        $row = $this->db->query("SELECT MAX(CAST(SUBSTRING(offer_no,4) AS UNSIGNED)) as maxn FROM offer_letters WHERE offer_no LIKE 'OFL%'")->fetch();
        $next = ($row['maxn'] ?? 0) + 1;
        return 'OFL' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
