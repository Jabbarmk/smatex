<?php
require_once 'app/core/Model.php';

class SettingsModel extends Model {
    protected $table = 'settings';

    public function getSetting($key) {
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = :key");
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : null;
    }

    public function setSetting($key, $value) {
        $stmt = $this->db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value");
        return $stmt->execute(['key' => $key, 'value' => $value]);
    }

    public function getAllSettings() {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM settings");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
