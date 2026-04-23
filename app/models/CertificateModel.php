<?php
require_once 'app/core/Model.php';

class CertificateModel extends Model {
    protected $table = 'certificates';

    public function getAll() {
        return $this->db->query("SELECT * FROM certificates ORDER BY created_at DESC")->fetchAll();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM certificates WHERE certificate_slug = :s LIMIT 1");
        $stmt->execute(['s' => $slug]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO certificates
                (certificate_no, certificate_slug, certificate_type, candidate_name, designation,
                 subject, duration_from, duration_to, description, issue_date,
                 issued_by, issued_by_title, created_by)
                VALUES
                (:certificate_no, :certificate_slug, :certificate_type, :candidate_name, :designation,
                 :subject, :duration_from, :duration_to, :description, :issue_date,
                 :issued_by, :issued_by_title, :created_by)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) return $this->db->lastInsertId();
        return false;
    }

    public function nextCertificateNo() {
        $count = $this->db->query("SELECT COUNT(*) as c FROM certificates")->fetch()['c'] ?? 0;
        return 'CERT-' . date('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function update($data) {
        $sql = "UPDATE certificates SET
                    certificate_no   = :certificate_no,
                    certificate_slug = :certificate_slug,
                    certificate_type = :certificate_type,
                    candidate_name   = :candidate_name,
                    designation      = :designation,
                    subject          = :subject,
                    duration_from    = :duration_from,
                    duration_to      = :duration_to,
                    description      = :description,
                    issue_date       = :issue_date,
                    issued_by        = :issued_by,
                    issued_by_title  = :issued_by_title
                WHERE id = :id";
        return $this->db->prepare($sql)->execute($data);
    }

    public function generateSlug($name) {
        return $this->generateSlugExcluding($name, null);
    }

    public function generateSlugExcluding($name, $excludeId) {
        $base = strtolower(trim($name));
        $base = preg_replace('/[^a-z0-9\s-]/', '', $base);
        $base = preg_replace('/[\s-]+/', '-', $base);
        $base = trim($base, '-');
        if ($base === '') $base = 'certificate';

        $slug = $base;
        $i = 1;
        while (true) {
            $sql  = "SELECT id FROM certificates WHERE certificate_slug = :s" . ($excludeId ? " AND id != :eid" : "") . " LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $params = ['s' => $slug];
            if ($excludeId) $params['eid'] = $excludeId;
            $stmt->execute($params);
            if (!$stmt->fetch()) return $slug;
            $i++;
            $slug = $base . '-' . $i;
        }
    }
}
