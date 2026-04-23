<?php
class StatementsController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    /** Landing page — two forms: pick salesman OR pick client */
    public function index() {
        require_once 'app/models/StatementsModel.php';
        require_once 'app/models/SettingsModel.php';

        $model    = new StatementsModel();
        $settings = (new SettingsModel())->getAllSettings();

        $this->view('statements/index', [
            'title'           => 'Statements',
            'currency_symbol' => $settings['currency_symbol'] ?? 'AED',
            'salesmen'        => $model->getAllSalesmen(),
            'clients'         => $model->getAllClients(),
        ]);
    }

    /** Sales Statement: pick salesman → view statement */
    public function salesman($id = null) {
        // Handle form POST (picker)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['salesman_id'] ?? 0);
            if (!$id) $this->redirect('statements');
            $this->redirect('statements/salesman/' . $id);
        }

        if (!$id) $this->redirect('statements');

        require_once 'app/models/StatementsModel.php';
        require_once 'app/models/SettingsModel.php';

        $model    = new StatementsModel();
        $settings = (new SettingsModel())->getAllSettings();

        $salesman  = $model->getSalesmanById($id);
        if (!$salesman) $this->redirect('statements');

        $invoices = $model->getSalesStatement($id);

        // Totals
        $totalAmount   = array_sum(array_column($invoices, 'grand_total'));
        $totalReceived = array_sum(array_column($invoices, 'amount_received'));
        $totalBalance  = $totalAmount - $totalReceived;
        $paidCount     = count(array_filter($invoices, fn($r) => $r['invoice_status'] === 'Paid'));
        $unpaidCount   = count($invoices) - $paidCount;

        $this->view('statements/salesman', [
            'title'           => 'Sales Statement — ' . $salesman['name'],
            'currency_symbol' => $settings['currency_symbol'] ?? 'AED',
            'company_name'    => $settings['company_name'] ?? '',
            'salesman'        => $salesman,
            'invoices'        => $invoices,
            'total_amount'    => $totalAmount,
            'total_received'  => $totalReceived,
            'total_balance'   => $totalBalance,
            'paid_count'      => $paidCount,
            'unpaid_count'    => $unpaidCount,
        ]);
    }

    /** Client Statement: pick client → view statement */
    public function client($id = null) {
        // Handle form POST (picker)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['client_id'] ?? 0);
            if (!$id) $this->redirect('statements');
            $this->redirect('statements/client/' . $id);
        }

        if (!$id) $this->redirect('statements');

        require_once 'app/models/StatementsModel.php';
        require_once 'app/models/SettingsModel.php';

        $model    = new StatementsModel();
        $settings = (new SettingsModel())->getAllSettings();

        $client = $model->getClientById($id);
        if (!$client) $this->redirect('statements');

        $invoices = $model->getClientStatement($id);

        // Totals
        $totalAmount   = array_sum(array_column($invoices, 'grand_total'));
        $totalReceived = array_sum(array_column($invoices, 'amount_received'));
        $totalBalance  = $totalAmount - $totalReceived;
        $paidCount     = count(array_filter($invoices, fn($r) => $r['invoice_status'] === 'Paid'));
        $unpaidCount   = count($invoices) - $paidCount;

        $this->view('statements/client', [
            'title'           => 'Client Statement — ' . ($client['company_name'] ?: $client['lead_name']),
            'currency_symbol' => $settings['currency_symbol'] ?? 'AED',
            'company_name'    => $settings['company_name'] ?? '',
            'client'          => $client,
            'invoices'        => $invoices,
            'total_amount'    => $totalAmount,
            'total_received'  => $totalReceived,
            'total_balance'   => $totalBalance,
            'paid_count'      => $paidCount,
            'unpaid_count'    => $unpaidCount,
        ]);
    }
}
