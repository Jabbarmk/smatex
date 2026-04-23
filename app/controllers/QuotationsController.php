<?php
class QuotationsController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/QuotationModel.php';
        $quotationModel = new QuotationModel();
        $quotations = $quotationModel->getAllWithLead();
        
        $this->view('quotations/index', ['quotations' => $quotations, 'title' => 'Quotations']);
    }

    public function create() {
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';
        
        $leadModel = new LeadModel();
        $settingsModel = new SettingsModel();
        
        $leads = $leadModel->getAllWithSalesManager();
        $settings = $settingsModel->getAllSettings();
        
        $nextId = rand(1000, 9999);
        $quotationNo = 'QT-' . date('Y') . '-' . $nextId;

        $this->view('quotations/create', [
            'leads' => $leads, 
            'quotation_no' => $quotationNo,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'tax_percentage' => $settings['tax_percentage'] ?? 5,
            'title' => 'Create Quotation'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/QuotationModel.php';
            require_once 'app/models/SettingsModel.php';
            $quotationModel = new QuotationModel();

            $applyTax = !empty($_POST['apply_tax']);
            $taxPct   = $applyTax ? floatval((new SettingsModel())->getAllSettings()['tax_percentage'] ?? 5) : 0;

            $subtotal = 0;
            $items    = [];

            $item_names   = $_POST['item_name'];
            $descriptions = $_POST['description'];
            $qtys         = $_POST['qty'];
            $prices       = $_POST['unit_price'];

            for ($i = 0; $i < count($item_names); $i++) {
                if (empty($item_names[$i])) continue;
                $line_total = $qtys[$i] * $prices[$i];
                $subtotal  += $line_total;
                $items[] = [
                    'item_name'   => $item_names[$i],
                    'description' => $descriptions[$i],
                    'qty'         => $qtys[$i],
                    'unit_price'  => $prices[$i],
                    'vat_percent' => $taxPct,
                    'line_total'  => $line_total
                ];
            }

            $vat_total   = round($subtotal * ($taxPct / 100), 2);
            $grand_total = $subtotal + $vat_total;

            $data = [
                'quotation_no'     => $_POST['quotation_no'],
                'lead_id'          => $_POST['lead_id'],
                'subtotal'         => $subtotal,
                'tax_percentage'   => $taxPct,
                'vat_total'        => $vat_total,
                'grand_total'      => $grand_total,
                'valid_until'      => $_POST['valid_until'],
                'status'           => 'Draft',
                'terms_conditions' => $_POST['terms_conditions'] ?? null,
                'created_by'       => $_SESSION['user_id']
            ];

            $quotation_id = $quotationModel->create($data);

            if ($quotation_id) {
                $quotationModel->addItems($quotation_id, $items);
                $this->redirect('quotations');
            } else {
                echo "Error generating quotation";
            }
        }
    }

    public function edit($id) {
        require_once 'app/models/QuotationModel.php';
        require_once 'app/models/LeadModel.php';

        $quotationModel = new QuotationModel();
        $leadModel = new LeadModel();

        $quotation = $quotationModel->find($id);
        if (!$quotation) $this->redirect('quotations');

        $items = $quotationModel->getItems($id);
        $leads = $leadModel->getAllWithSalesManager();

        require_once 'app/models/SettingsModel.php';
        $settings = (new SettingsModel())->getAllSettings();

        $this->view('quotations/edit', [
            'quotation'      => $quotation,
            'items'          => $items,
            'leads'          => $leads,
            'tax_percentage' => $settings['tax_percentage'] ?? 5,
            'title'          => 'Edit Quotation'
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/QuotationModel.php';
            require_once 'app/models/SettingsModel.php';
            $quotationModel = new QuotationModel();

            $applyTax = !empty($_POST['apply_tax']);
            $taxPct   = $applyTax ? floatval((new SettingsModel())->getAllSettings()['tax_percentage'] ?? 5) : 0;

            $subtotal = 0;
            $items    = [];

            $item_names   = $_POST['item_name'];
            $descriptions = $_POST['description'];
            $qtys         = $_POST['qty'];
            $prices       = $_POST['unit_price'];

            for ($i = 0; $i < count($item_names); $i++) {
                if (empty($item_names[$i])) continue;
                $line_total = $qtys[$i] * $prices[$i];
                $subtotal  += $line_total;
                $items[] = [
                    'item_name'   => $item_names[$i],
                    'description' => $descriptions[$i],
                    'qty'         => $qtys[$i],
                    'unit_price'  => $prices[$i],
                    'vat_percent' => $taxPct,
                    'line_total'  => $line_total
                ];
            }

            $vat_total   = round($subtotal * ($taxPct / 100), 2);
            $grand_total = $subtotal + $vat_total;

            $data = [
                'lead_id'          => $_POST['lead_id'],
                'subtotal'         => $subtotal,
                'tax_percentage'   => $taxPct,
                'vat_total'        => $vat_total,
                'grand_total'      => $grand_total,
                'valid_until'      => $_POST['valid_until'],
                'status'           => $_POST['status'],
                'terms_conditions' => $_POST['terms_conditions'] ?? null
            ];

            if ($quotationModel->update($id, $data)) {
                $quotationModel->deleteItems($id);
                $quotationModel->addItems($id, $items);
                $this->redirect('quotations');
            } else {
                echo "Error updating quotation";
            }
        }
    }

    public function show($id) {
        require_once 'app/models/QuotationModel.php';
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';
        
        $quotationModel = new QuotationModel();
        $leadModel = new LeadModel();
        $settingsModel = new SettingsModel();
        
        $quotation = $quotationModel->find($id);
        if (!$quotation) $this->redirect('quotations');
        
        $items = $quotationModel->getItems($id);
        $lead = $leadModel->find($quotation['lead_id']);
        $settings = $settingsModel->getAllSettings();
        
        $this->view('quotations/view', [
            'quotation' => $quotation, 
            'items' => $items, 
            'lead' => $lead, 
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'tax_percentage' => $settings['tax_percentage'] ?? 5,
            'title' => 'Quotation #' . $quotation['quotation_no']
        ]);
    }

    public function delete($id) {
        require_once 'app/models/QuotationModel.php';
        $quotationModel = new QuotationModel();
        $quotationModel->delete($id);
        $this->redirect('quotations');
    }
}
