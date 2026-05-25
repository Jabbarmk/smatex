<?php
class InvoicesController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();
        $invoices = $invoiceModel->getAllWithLead();
        
        $this->view('invoices/index', ['invoices' => $invoices, 'title' => 'Invoices & Revenue']);
    }

    public function create() {
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';
        require_once 'app/models/QuotationModel.php';

        $leadModel = new LeadModel();
        $settingsModel = new SettingsModel();
        $quotationModel = new QuotationModel();

        $leads = $leadModel->getAllWithSalesManager();
        $settings = $settingsModel->getAllSettings();
        $quotations = $quotationModel->getAllWithLead();

        // Generate Invoice Number
        $nextId = rand(1000, 9999);
        $invoiceNo = 'INV-' . date('Y') . '-' . $nextId;

        $this->view('invoices/create', [
            'leads' => $leads,
            'quotations' => $quotations,
            'invoice_no' => $invoiceNo,
            'tax_percentage' => $settings['tax_percentage'] ?? 5,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title' => 'Create Invoice'
        ]);
    }

    public function getQuotation($id) {
        require_once 'app/models/QuotationModel.php';
        $quotationModel = new QuotationModel();

        $quotation = $quotationModel->find($id);
        if (!$quotation) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Quotation not found']);
            return;
        }

        $items = $quotationModel->getItems($id);

        header('Content-Type: application/json');
        echo json_encode([
            'quotation' => $quotation,
            'items' => $items
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/InvoiceModel.php';
            $invoiceModel = new InvoiceModel();

            // Calculate totals
            $subtotal = 0;
            $items = [];
            
            $item_names = $_POST['item_name'];
            $descriptions = $_POST['description'];
            $qtys = $_POST['qty'];
            $prices = $_POST['unit_price'];

            for ($i = 0; $i < count($item_names); $i++) {
                if (empty($item_names[$i])) continue;

                $line_total = $qtys[$i] * $prices[$i];
                $subtotal += $line_total;

                $items[] = [
                    'item_name' => $item_names[$i],
                    'description' => $descriptions[$i],
                    'qty' => $qtys[$i],
                    'unit_price' => $prices[$i],
                    'vat_percent' => 0, // Individual tax deprecated in favor of global tax
                    'line_total' => $line_total 
                ];
            }

            $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
            $tax_enabled = isset($_POST['tax_enabled']) ? true : false;
            $tax_percentage = $tax_enabled ? floatval($_POST['tax_percentage']) : 0;
            
            $taxable_amount = $subtotal - $discount;
            if ($taxable_amount < 0) $taxable_amount = 0;
            
            $vat_total = $taxable_amount * ($tax_percentage / 100);
            $grand_total = $taxable_amount + $vat_total;

            $data = [
                'invoice_no' => $_POST['invoice_no'],
                'lead_id' => $_POST['lead_id'],
                'client_details' => $_POST['client_details'] ?? '',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax_percentage' => $tax_percentage,
                'vat_total' => $vat_total,
                'grand_total' => $grand_total,
                'due_date' => $_POST['due_date'],
                'payment_terms' => $_POST['payment_terms'],
                'status' => 'Unpaid',
                'created_by' => $_SESSION['user_id']
            ];

            $invoice_id = $invoiceModel->create($data);

            if ($invoice_id) {
                // Update item VAT percent just for record roughly, though global tax is used
                foreach ($items as &$item) {
                    $item['vat_percent'] = $tax_percentage;
                }
                $invoiceModel->addItems($invoice_id, $items);
                $this->redirect('invoices');
            } else {
                echo "Error generating invoice";
            }
        }
    }
    public function show($id) {
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';
        
        $invoiceModel = new InvoiceModel();
        $leadModel = new LeadModel();
        $settingsModel = new SettingsModel();
        
        $invoice = $invoiceModel->find($id);
        if (!$invoice) $this->redirect('invoices');
        
        $items = $invoiceModel->getItems($id);
        $lead = $leadModel->find($invoice['lead_id']);
        $settings = $settingsModel->getAllSettings();
        
        $this->view('invoices/view', [
            'invoice' => $invoice, 
            'items' => $items, 
            'lead' => $lead,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'settings' => $settings,
            'title' => 'Invoice #' . $invoice['invoice_no']
        ]);
    }

    public function edit($id) {
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';

        $invoiceModel = new InvoiceModel();
        $leadModel = new LeadModel();
        $settingsModel = new SettingsModel();

        $invoice = $invoiceModel->find($id);
        if (!$invoice) $this->redirect('invoices');

        $items = $invoiceModel->getItems($id);
        $leads = $leadModel->getAllWithSalesManager();
        $settings = $settingsModel->getAllSettings();

        $this->view('invoices/edit', [
            'invoice' => $invoice,
            'items' => $items,
            'leads' => $leads,
            'tax_percentage' => $settings['tax_percentage'] ?? 5,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title' => 'Edit Invoice'
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/InvoiceModel.php';
            $invoiceModel = new InvoiceModel();

            // Calculate totals
            $subtotal = 0;
            $items = [];
            
            $item_names = $_POST['item_name'];
            $descriptions = $_POST['description'];
            $qtys = $_POST['qty'];
            $prices = $_POST['unit_price'];

            for ($i = 0; $i < count($item_names); $i++) {
                if (empty($item_names[$i])) continue;

                $line_total = $qtys[$i] * $prices[$i];
                $subtotal += $line_total;

                $items[] = [
                    'item_name' => $item_names[$i],
                    'description' => $descriptions[$i],
                    'qty' => $qtys[$i],
                    'unit_price' => $prices[$i],
                    'vat_percent' => 0,
                    'line_total' => $line_total
                ];
            }

            $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
            $tax_enabled = isset($_POST['tax_enabled']) ? true : false;
            $tax_percentage = $tax_enabled ? floatval($_POST['tax_percentage']) : 0;
            
            $taxable_amount = $subtotal - $discount;
            if ($taxable_amount < 0) $taxable_amount = 0;
            
            $vat_total = $taxable_amount * ($tax_percentage / 100);
            $grand_total = $taxable_amount + $vat_total;

            $data = [
                'lead_id' => $_POST['lead_id'],
                'client_details' => $_POST['client_details'] ?? '',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax_percentage' => $tax_percentage,
                'vat_total' => $vat_total,
                'grand_total' => $grand_total,
                'due_date' => $_POST['due_date'],
                'payment_terms' => $_POST['payment_terms'],
                'status' => $_POST['status']
            ];

            if ($invoiceModel->update($id, $data)) {
                 // Update item VAT percent just for record roughly
                foreach ($items as &$item) {
                    $item['vat_percent'] = $tax_percentage;
                }
                $invoiceModel->deleteItems($id);
                $invoiceModel->addItems($id, $items);
                $this->redirect('invoices/show/' . $id);
            } else {
                echo "Error updating invoice";
            }
        }
    }

    public function delete($id) {
        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();
        // Models find method available, but generic delete also needed.
        // Assuming delete method in base model works or implemented in model.
        // Base Model delete handles simple delete by ID.
        $invoiceModel->delete($id);
        $this->redirect('invoices');
    }
}

