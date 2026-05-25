<?php
require_once 'app/core/Model.php';

class ContractModel extends Model {
    protected $table = 'contracts';

    public function getAll() {
        $sql = "SELECT c.*, u.name AS created_by_name
                FROM contracts c
                LEFT JOIN users u ON c.created_by = u.id
                ORDER BY c.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO contracts (
                    contract_no, title, contract_type,
                    first_party_name, first_party_address, first_party_phone, first_party_email, first_party_representative, first_party_designation,
                    second_party_name, second_party_address, second_party_phone, second_party_email, second_party_representative, second_party_designation,
                    start_date, end_date, value, contents, terms_conditions, notes, status, created_by
                ) VALUES (
                    :contract_no, :title, :contract_type,
                    :first_party_name, :first_party_address, :first_party_phone, :first_party_email, :first_party_representative, :first_party_designation,
                    :second_party_name, :second_party_address, :second_party_phone, :second_party_email, :second_party_representative, :second_party_designation,
                    :start_date, :end_date, :value, :contents, :terms_conditions, :notes, :status, :created_by
                )";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $sql = "UPDATE contracts SET
                    title = :title, contract_type = :contract_type,
                    first_party_name = :first_party_name, first_party_address = :first_party_address,
                    first_party_phone = :first_party_phone, first_party_email = :first_party_email,
                    first_party_representative = :first_party_representative, first_party_designation = :first_party_designation,
                    second_party_name = :second_party_name, second_party_address = :second_party_address,
                    second_party_phone = :second_party_phone, second_party_email = :second_party_email,
                    second_party_representative = :second_party_representative, second_party_designation = :second_party_designation,
                    start_date = :start_date, end_date = :end_date, value = :value,
                    contents = :contents, terms_conditions = :terms_conditions, notes = :notes, status = :status
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
