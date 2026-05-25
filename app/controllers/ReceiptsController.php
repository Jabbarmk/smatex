<?php
class ReceiptsController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function create($invoice_id = null) {
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/ReceiptModel.php';
        
        $invoiceModel = new InvoiceModel();
        $receiptModel = new ReceiptModel();
        require_once 'app/models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();

        $invoice = null;
        $invoices = [];
        $balanceDue = 0;

        if ($invoice_id) {
            $invoice = $invoiceModel->find($invoice_id);
            if (!$invoice) $this->redirect('invoices');
            
            $totalPaid = $receiptModel->getTotalPaid($invoice_id);
            $balanceDue = $invoice['grand_total'] - $totalPaid;
        } else {
            $invoices = $invoiceModel->getUnpaid();
            // Calculate balance for each invoice for the dropdown (simplified, might be heavy if many invoices)
            // Or better, do it via AJAX. For now let's just pass invoice list.
        }

        $nextId = rand(1000, 9999);
        $receiptNo = 'REC-' . date('Y') . '-' . $nextId;

        $this->view('receipts/create', [
            'invoice' => $invoice,
            'invoices' => $invoices,
            'receipt_no' => $receiptNo,
            'balance_due' => $balanceDue,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title' => 'Record Payment'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/ReceiptModel.php';
            require_once 'app/models/InvoiceModel.php';

            $receiptModel = new ReceiptModel();
            $invoiceModel = new InvoiceModel();

            $data = [
                'receipt_no' => $_POST['receipt_no'],
                'invoice_id' => $_POST['invoice_id'],
                'payment_date' => $_POST['payment_date'],
                'amount_paid' => $_POST['amount_paid'],
                'payment_mode' => $_POST['payment_mode'],
                'reference_number' => $_POST['reference_number'],
                'notes' => $_POST['notes'],
                'created_by' => $_SESSION['user_id']
            ];

            if ($receiptModel->create($data)) {
                // Update Invoice Status
                $invoice_id = $_POST['invoice_id'];
                $invoice = $invoiceModel->find($invoice_id);
                $totalPaid = $receiptModel->getTotalPaid($invoice_id);
                
                $status = 'Unpaid';
                if ($totalPaid >= $invoice['grand_total']) {
                    $status = 'Paid';
                } elseif ($totalPaid > 0) {
                    $status = 'Partial';
                }
                
                $invoiceModel->updateStatus($invoice_id, $status);
                
                $this->redirect('invoices/show/' . $invoice_id);
            } else {
                echo "Error recording receipt";
            }
        }
    }
    public function index() {
        require_once 'app/models/ReceiptModel.php';
        require_once 'app/models/SettingsModel.php';
        
        $receiptModel = new ReceiptModel();
        $settingsModel = new SettingsModel();
        
        $month = $_GET['month'] ?? date('Y-m');
        $date = $_GET['date'] ?? null;
        $showAll = isset($_GET['show_all']);
        
        if ($showAll) {
             $receipts = $receiptModel->filter(null, null);
             $filterLabel = "All Time Receipts";
             $month = '';
             $date = '';
        } elseif (!empty($date)) {
            $receipts = $receiptModel->filter(null, $date);
            $filterLabel = "Date: " . $date;
            $month = ''; 
        } elseif (!empty($month)) {
            $receipts = $receiptModel->filter($month, null);
            $filterLabel = "Month: " . date('F Y', strtotime($month));
        } else {
            $receipts = $receiptModel->filter(date('Y-m'), null);
            $filterLabel = "Current Month";
        }
        
        $settings = $settingsModel->getAllSettings();
        
        $this->view('receipts/index', [
            'receipts' => $receipts, 
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title' => 'Receipts',
            'current_month' => $month,
            'current_date' => $date,
            'filter_label' => $filterLabel
        ]);
    }

    public function show($id) {
        require_once 'app/models/ReceiptModel.php';
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/SettingsModel.php';

        $receiptModel  = new ReceiptModel();
        $invoiceModel  = new InvoiceModel();
        $leadModel     = new LeadModel();
        $settingsModel = new SettingsModel();

        $receipt = $receiptModel->find($id);
        if (!$receipt) $this->redirect('receipts');

        $invoice     = $invoiceModel->find($receipt['invoice_id']);
        $lead        = $leadModel->find($invoice['lead_id']);
        $settings    = $settingsModel->getAllSettings();
        $totalPaid   = $receiptModel->getTotalPaid($invoice['id']);
        $balanceDue  = $invoice['grand_total'] - $totalPaid;

        $this->view('receipts/view', [
            'receipt'    => $receipt,
            'invoice'    => $invoice,
            'lead'       => $lead,
            'settings'   => $settings,
            'total_paid' => $totalPaid,
            'balance_due'=> $balanceDue,
            'title'      => 'Receipt ' . $receipt['receipt_no']
        ]);
    }

    public function edit($id) {
        require_once 'app/models/ReceiptModel.php';
        require_once 'app/models/InvoiceModel.php';
        require_once 'app/models/SettingsModel.php';
        
        $receiptModel = new ReceiptModel();
        $invoiceModel = new InvoiceModel();
        $settingsModel = new SettingsModel();
        
        $receipt = $receiptModel->find($id);
        if (!$receipt) $this->redirect('receipts');
        
        $invoice = $invoiceModel->find($receipt['invoice_id']);
        $totalPaid = $receiptModel->getTotalPaid($invoice['id']);
        $settings = $settingsModel->getAllSettings();
        
        // Balance excluding current receipt amount (to allow editing amount up to this limit + previous amount)
        $balanceDue = ($invoice['grand_total'] - $totalPaid) + $receipt['amount_paid'];

        $this->view('receipts/edit', [
            'receipt' => $receipt,
            'invoice' => $invoice,
            'balance_due' => $balanceDue,
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
            'title' => 'Edit Receipt'
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/ReceiptModel.php';
            require_once 'app/models/InvoiceModel.php';
            
            $receiptModel = new ReceiptModel();
            $invoiceModel = new InvoiceModel();

            $data = [
                'payment_date' => $_POST['payment_date'],
                'amount_paid' => $_POST['amount_paid'],
                'payment_mode' => $_POST['payment_mode'],
                'reference_number' => $_POST['reference_number'],
                'notes' => $_POST['notes']
            ];

            if ($receiptModel->update($id, $data)) {
                // Recalculate Invoice Status
                $receipt = $receiptModel->find($id);
                $invoice_id = $receipt['invoice_id'];
                $invoice = $invoiceModel->find($invoice_id);
                $totalPaid = $receiptModel->getTotalPaid($invoice_id);
                
                $status = 'Unpaid';
                if ($totalPaid >= $invoice['grand_total']) {
                    $status = 'Paid';
                } elseif ($totalPaid > 0) {
                    $status = 'Partial';
                }
                $invoiceModel->updateStatus($invoice_id, $status);

                $this->redirect('receipts');
            } else {
                echo "Error updating receipt";
            }
        }
    }

    public function delete($id) {
        require_once 'app/models/ReceiptModel.php';
        require_once 'app/models/InvoiceModel.php';
        
        $receiptModel = new ReceiptModel();
        $invoiceModel = new InvoiceModel();
        
        $receipt = $receiptModel->find($id);
        if ($receipt) {
            $invoice_id = $receipt['invoice_id'];
            $receiptModel->delete($id);
            
            // Recalculate Status
            $invoice = $invoiceModel->find($invoice_id);
            $totalPaid = $receiptModel->getTotalPaid($invoice_id);
            
            $status = 'Unpaid';
            if ($totalPaid >= $invoice['grand_total']) {
                $status = 'Paid';
            } elseif ($totalPaid > 0) {
                $status = 'Partial';
            }
            $invoiceModel->updateStatus($invoice_id, $status);
        }
        
        $this->redirect('receipts');
    }
}
