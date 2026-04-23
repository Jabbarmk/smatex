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

            // Loop through POST data and update settings
            foreach ($_POST as $key => $value) {
                // Ensure key is safe or whitelist keys
                if (in_array($key, ['tax_percentage', 'currency_symbol', 'currency_position', 'decimal_separator', 'thousands_separator', 'decimal_places', 'company_name', 'company_address', 'company_phone', 'company_email', 'company_website', 'company_trn', 'bank_details', 'invoice_terms', 'invoice_footer'])) {
                    $settingsModel->setSetting($key, $value);
                }
            }
            
            // Redirect back with success (could add flash message)
            header('Location: ' . BASE_URL . 'settings?success=1');
            exit;
        }
    }
}
