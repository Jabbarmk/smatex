<?php
class InvoicesController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();
        $invoices       = $invoiceModel->getAllWithLeadReal();
        $drafts         = $invoiceModel->getAllWithLeadDraft();
        $summaryStats   = $invoiceModel->getSummaryStats();
        $uniqueClients  = $invoiceModel->getUniqueClients();

        $this->view('invoices/index', [
            'invoices'      => $invoices,
            'drafts'        => $drafts,
            'summaryStats'  => $summaryStats,
            'uniqueClients' => $uniqueClients,
            'title'         => 'Invoices & Revenue',
        ]);
    }

    public function create($isDraft = 0) {
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';
        require_once 'app/models/QuotationModel.php';
        require_once 'app/models/InvoiceModel.php';

        $leadModel     = new LeadModel();
        $settingsModel = new SettingsModel();
        $quotationModel = new QuotationModel();
        $invoiceModel  = new InvoiceModel();

        $leads     = $leadModel->getAllWithSalesManager();
        $settings  = $settingsModel->getAllSettings();
        $quotations = $quotationModel->getAllWithLead();
        $isDraft   = (int)$isDraft;

        $invoiceNo = $isDraft ? $invoiceModel->getNextDraftNo() : $invoiceModel->getNextInvoiceNo();

        $this->view('invoices/create', [
            'leads'           => $leads,
            'quotations'      => $quotations,
            'invoice_no'      => $invoiceNo,
            'is_draft'        => $isDraft,
            'tax_percentage'  => $settings['tax_percentage'] ?? 5,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title'           => $isDraft ? 'Create Draft Invoice' : 'Create Invoice',
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
        echo json_encode(['quotation' => $quotation, 'items' => $items]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();

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
            $items[]    = [
                'item_name'   => $item_names[$i],
                'description' => $descriptions[$i],
                'qty'         => $qtys[$i],
                'unit_price'  => $prices[$i],
                'vat_percent' => 0,
                'line_total'  => $line_total,
            ];
        }

        $discount       = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
        $tax_enabled    = !empty($_POST['tax_enabled']);
        $tax_percentage = $tax_enabled ? floatval($_POST['tax_percentage']) : 0;
        $taxable_amount = max(0, $subtotal - $discount);
        $vat_total      = $taxable_amount * ($tax_percentage / 100);
        $grand_total    = $taxable_amount + $vat_total;
        $is_draft       = isset($_POST['is_draft']) ? (int)$_POST['is_draft'] : 0;

        $data = [
            'invoice_no'     => $_POST['invoice_no'],
            'lead_id'        => $_POST['lead_id'],
            'quotation_id'   => !empty($_POST['quotation_id']) ? (int)$_POST['quotation_id'] : null,
            'client_details' => $_POST['client_details'] ?? '',
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax_percentage' => $tax_percentage,
            'vat_total'      => $vat_total,
            'grand_total'    => $grand_total,
            'due_date'       => $_POST['due_date'],
            'payment_terms'  => $_POST['payment_terms'],
            'status'         => 'Unpaid',
            'is_draft'       => $is_draft,
            'created_by'     => $_SESSION['user_id'],
        ];

        $invoice_id = $invoiceModel->create($data);

        if ($invoice_id) {
            foreach ($items as &$item) {
                $item['vat_percent'] = $tax_percentage;
            }
            $invoiceModel->addItems($invoice_id, $items);
            $this->redirect('invoices/show/' . $invoice_id);
        } else {
            echo "Error generating invoice";
        }
    }

    public function show($id) {
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';

        $invoiceModel  = new InvoiceModel();
        $leadModel     = new LeadModel();
        $settingsModel = new SettingsModel();

        $invoice = $invoiceModel->find($id);
        if (!$invoice) $this->redirect('invoices');

        $items    = $invoiceModel->getItems($id);
        $lead     = $leadModel->find($invoice['lead_id']);
        $settings = $settingsModel->getAllSettings();

        $this->view('invoices/view', [
            'invoice'         => $invoice,
            'items'           => $items,
            'lead'            => $lead,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'settings'        => $settings,
            'title'           => ($invoice['is_draft'] ? 'Draft ' : '') . 'Invoice #' . $invoice['invoice_no'],
        ]);
    }

    public function promote($id) {
        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();
        $invoice = $invoiceModel->find($id);
        if (!$invoice || !$invoice['is_draft']) $this->redirect('invoices');

        $newNo = $invoiceModel->getNextInvoiceNo();
        $invoiceModel->promote($id, $newNo);
        $this->redirect('invoices/show/' . $id);
    }

    public function edit($id) {
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';

        $invoiceModel  = new InvoiceModel();
        $leadModel     = new LeadModel();
        $settingsModel = new SettingsModel();

        $invoice = $invoiceModel->find($id);
        if (!$invoice) $this->redirect('invoices');

        $items    = $invoiceModel->getItems($id);
        $leads    = $leadModel->getAllWithSalesManager();
        $settings = $settingsModel->getAllSettings();

        $this->view('invoices/edit', [
            'invoice'         => $invoice,
            'items'           => $items,
            'leads'           => $leads,
            'tax_percentage'  => $settings['tax_percentage'] ?? 5,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title'           => 'Edit Invoice',
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();

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
            $items[]    = [
                'item_name'   => $item_names[$i],
                'description' => $descriptions[$i],
                'qty'         => $qtys[$i],
                'unit_price'  => $prices[$i],
                'vat_percent' => 0,
                'line_total'  => $line_total,
            ];
        }

        $discount       = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
        $tax_enabled    = !empty($_POST['tax_enabled']);
        $tax_percentage = $tax_enabled ? floatval($_POST['tax_percentage']) : 0;
        $taxable_amount = max(0, $subtotal - $discount);
        $vat_total      = $taxable_amount * ($tax_percentage / 100);
        $grand_total    = $taxable_amount + $vat_total;

        $existing = $invoiceModel->find($id);
        $data = [
            'lead_id'        => $_POST['lead_id'],
            'client_details' => $_POST['client_details'] ?? '',
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax_percentage' => $tax_percentage,
            'vat_total'      => $vat_total,
            'grand_total'    => $grand_total,
            'due_date'       => $_POST['due_date'],
            'payment_terms'  => $_POST['payment_terms'],
            'status'         => $_POST['status'],
        ];

        // Allow draft number to be edited
        if (!empty($existing['is_draft']) && !empty($_POST['invoice_no'])) {
            $data['invoice_no'] = trim($_POST['invoice_no']);
        }

        // Invoice date is admin-only, server-side enforced regardless of what the form sends
        if (($_SESSION['user_role'] ?? '') === 'Super Admin' && !empty($_POST['invoice_date'])) {
            $data['created_at'] = $_POST['invoice_date'] . ' ' . date('H:i:s', strtotime($existing['created_at']));
        }

        if ($invoiceModel->update($id, $data)) {
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

    public function delete($id) {
        require_once 'app/models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();
        $invoiceModel->delete($id);
        $this->redirect('invoices');
    }
}
