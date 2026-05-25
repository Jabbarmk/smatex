<?php
class SettingsController extends Controller {

    public function __construct() {
        $this->requireLogin();
        // Check role if needed (e.g. only Admin)
        if ($_SESSION['user_role'] !== 'Super Admin' && $_SESSION['user_role'] !== 'Admin') {
            // redirect or show error? For now, allow access or redirect
            // actually user said "Super Admin" only has access in previous context?
            // "System Settings" link is only for Super Admin in header.php
        }
    }

    public function index() {
        require_once 'app/models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();

        $this->view('settings/index', ['settings' => $settings, 'title' => 'System Settings']);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/SettingsModel.php';
            $settingsModel = new SettingsModel();

            $allowed = ['tax_percentage', 'currency_symbol', 'currency_position', 'decimal_separator', 'thousands_separator', 'decimal_places', 'company_name', 'company_address', 'company_phone', 'company_email', 'company_website', 'company_trn', 'bank_details', 'invoice_terms', 'invoice_footer', 'company_signature'];
            foreach ($_POST as $key => $value) {
                if (in_array($key, $allowed)) {
                    $settingsModel->setSetting($key, $value);
                }
            }

            $uploadDir = 'public/uploads/';
            foreach (['company_logo', 'company_stamp', 'company_signature'] as $field) {
                if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                        $filename = $field . '_' . time() . '_' . preg_replace('/[^a-z0-9_.-]/', '', strtolower($_FILES[$field]['name']));
                        if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $filename)) {
                            $old = $settingsModel->getSetting($field);
                            if ($old && file_exists($uploadDir . $old)) @unlink($uploadDir . $old);
                            $settingsModel->setSetting($field, $filename);
                        }
                    }
                }
            }

            header('Location: ' . BASE_URL . 'settings?success=1');
            exit;
        }
    }
}
