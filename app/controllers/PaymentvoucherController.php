<?php
class PaymentvoucherController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/PaymentVoucherModel.php';
        require_once 'app/models/SettingsModel.php';
        $this->view('paymentvoucher/index', [
            'title'    => 'Payment Vouchers',
            'vouchers' => (new PaymentVoucherModel())->getAllWithDetails(),
            'settings' => (new SettingsModel())->getAllSettings(),
        ]);
    }

    public function create() {
        require_once 'app/models/PaymentVoucherModel.php';
        require_once 'app/models/StatementsModel.php';
        require_once 'app/models/SettingsModel.php';
        $this->view('paymentvoucher/create', [
            'title'      => 'Create Payment Voucher',
            'voucher_no' => (new PaymentVoucherModel())->nextVoucherNo(),
            'clients'    => (new StatementsModel())->getAllClients(),
            'settings'   => (new SettingsModel())->getAllSettings(),
        ]);
    }

    /** AJAX: return invoices for a lead as JSON */
    public function invoices() {
        $this->requireLogin();
        $lead_id = isset($_GET['lead_id']) ? (int)$_GET['lead_id'] : 0;
        if (!$lead_id) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }
        require_once 'app/models/PaymentVoucherModel.php';
        $rows = (new PaymentVoucherModel())->getInvoicesForLead($lead_id);
        header('Content-Type: application/json');
        echo json_encode($rows);
        exit;
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/PaymentVoucherModel.php';

        $model = new PaymentVoucherModel();

        $data = [
            'voucher_no'       => trim($_POST['voucher_no']),
            'lead_id'          => intval($_POST['lead_id']),
            'payment_date'     => $_POST['payment_date'],
            'payment_mode'     => $_POST['payment_mode'],
            'amount'           => floatval($_POST['amount']),
            'reference_number' => trim($_POST['reference_number'] ?? ''),
            'description'      => trim($_POST['description'] ?? ''),
            'notes'            => trim($_POST['notes'] ?? ''),
            'created_by'       => $_SESSION['user_id'],
        ];

        $id = $model->create($data);
        if ($id) {
            $descs   = $_POST['item_desc']   ?? [];
            $invNos  = $_POST['item_inv_no'] ?? [];
            $amounts = $_POST['item_amount'] ?? [];

            foreach ($descs as $i => $desc) {
                $desc = trim($desc);
                if ($desc === '') continue;
                $model->createItem([
                    'voucher_id'  => $id,
                    'invoice_no'  => trim($invNos[$i] ?? ''),
                    'description' => $desc,
                    'amount'      => floatval($amounts[$i] ?? 0),
                ]);
            }
            $this->redirect('paymentvoucher/show/' . $id);
        } else {
            $this->redirect('paymentvoucher/create?error=1');
        }
    }

    public function edit($id) {
        require_once 'app/models/PaymentVoucherModel.php';
        require_once 'app/models/StatementsModel.php';
        require_once 'app/models/SettingsModel.php';

        $model   = new PaymentVoucherModel();
        $voucher = $model->findWithDetails($id);
        if (!$voucher) $this->redirect('paymentvoucher');

        $this->view('paymentvoucher/edit', [
            'title'    => 'Edit Voucher ' . $voucher['voucher_no'],
            'voucher'  => $voucher,
            'items'    => $model->getItems($id),
            'clients'  => (new StatementsModel())->getAllClients(),
            'settings' => (new SettingsModel())->getAllSettings(),
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/PaymentVoucherModel.php';

        $model = new PaymentVoucherModel();
        if (!$model->findWithDetails($id)) $this->redirect('paymentvoucher');

        $data = [
            'voucher_no'       => trim($_POST['voucher_no']),
            'lead_id'          => intval($_POST['lead_id']),
            'payment_date'     => $_POST['payment_date'],
            'payment_mode'     => $_POST['payment_mode'],
            'amount'           => floatval($_POST['amount']),
            'reference_number' => trim($_POST['reference_number'] ?? ''),
            'description'      => trim($_POST['description'] ?? ''),
            'notes'            => trim($_POST['notes'] ?? ''),
        ];

        $model->update($id, $data);

        // Replace all items
        $model->deleteItems($id);
        $descs   = $_POST['item_desc']   ?? [];
        $invNos  = $_POST['item_inv_no'] ?? [];
        $amounts = $_POST['item_amount'] ?? [];
        foreach ($descs as $i => $desc) {
            $desc = trim($desc);
            if ($desc === '') continue;
            $model->createItem([
                'voucher_id'  => $id,
                'invoice_no'  => trim($invNos[$i] ?? ''),
                'description' => $desc,
                'amount'      => floatval($amounts[$i] ?? 0),
            ]);
        }

        $this->redirect('paymentvoucher/show/' . $id);
    }

    public function show($id) {
        require_once 'app/models/PaymentVoucherModel.php';
        require_once 'app/models/SettingsModel.php';

        $model   = new PaymentVoucherModel();
        $voucher = $model->findWithDetails($id);
        if (!$voucher) $this->redirect('paymentvoucher');

        $this->view('paymentvoucher/voucher', [
            'title'    => 'Payment Voucher ' . $voucher['voucher_no'],
            'voucher'  => $voucher,
            'items'    => $model->getItems($id),
            'settings' => (new SettingsModel())->getAllSettings(),
        ]);
    }

    public function delete($id) {
        require_once 'app/models/PaymentVoucherModel.php';
        (new PaymentVoucherModel())->delete($id);
        $this->redirect('paymentvoucher');
    }
}
