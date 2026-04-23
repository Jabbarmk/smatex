<?php
require_once 'app/core/Model.php';

class QuotationModel extends Model {
    protected $table = 'quotations';

    public function getAllWithLead() {
        $sql = "SELECT q.*, l.lead_name, l.company_name 
                FROM quotations q 
                LEFT JOIN leads l ON q.lead_id = l.id 
                ORDER BY q.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO quotations (quotation_no, lead_id, subtotal, tax_percentage, vat_total, grand_total, valid_until, status, terms_conditions, created_by)
                VALUES (:quotation_no, :lead_id, :subtotal, :tax_percentage, :vat_total, :grand_total, :valid_until, :status, :terms_conditions, :created_by)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) return $this->db->lastInsertId();
        return false;
    }

    public function update($id, $data) {
        $sql = "UPDATE quotations SET lead_id=:lead_id, subtotal=:subtotal, tax_percentage=:tax_percentage, vat_total=:vat_total, grand_total=:grand_total, valid_until=:valid_until, status=:status, terms_conditions=:terms_conditions WHERE id=:id";
        $data['id'] = $id;
        return $this->db->prepare($sql)->execute($data);
    }
    
    public function addItems($quotation_id, $items) {
        $sql = "INSERT INTO quotation_items (quotation_id, item_name, description, qty, unit_price, vat_percent, line_total) 
                VALUES (:quotation_id, :item_name, :description, :qty, :unit_price, :vat_percent, :line_total)";
        $stmt = $this->db->prepare($sql);

        foreach ($items as $item) {
            $item['quotation_id'] = $quotation_id;
            $stmt->execute($item);
        }
    }

    public function getItems($quotation_id) {
        $stmt = $this->db->prepare("SELECT * FROM quotation_items WHERE quotation_id = :quotation_id");
        $stmt->execute(['quotation_id' => $quotation_id]);
        return $stmt->fetchAll();
    }

    public function deleteItems($quotation_id) {
        $stmt = $this->db->prepare("DELETE FROM quotation_items WHERE quotation_id = :quotation_id");
        return $stmt->execute(['quotation_id' => $quotation_id]);
    }
}
