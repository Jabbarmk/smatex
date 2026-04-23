<?php
class SalaryController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/SalaryPaymentModel.php';
        $model = new SalaryPaymentModel();
        $this->view('salary/index', [
            'payments' => $model->getAllWithEmployee(),
            'title'    => 'Salary Payments'
        ]);
    }

    public function create() {
        require_once 'app/models/EmployeeModel.php';
        require_once 'app/models/SalaryPaymentModel.php';
        $employees  = (new EmployeeModel())->getActive();
        $voucher_no = (new SalaryPaymentModel())->nextVoucherNo();

        $months = ['January','February','March','April','May','June',
                   'July','August','September','October','November','December'];

        $this->view('salary/create', [
            'employees'  => $employees,
            'voucher_no' => $voucher_no,
            'months'     => $months,
            'title'      => 'Create Salary Voucher'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/SalaryPaymentModel.php';
        $model = new SalaryPaymentModel();

        $basic     = floatval($_POST['basic_salary']);
        $housing   = floatval($_POST['housing_allowance']);
        $transport = floatval($_POST['transport_allowance']);
        $other     = floatval($_POST['other_allowance']);
        $gross     = $basic + $housing + $transport + $other;
        $deductions = floatval($_POST['deductions'] ?? 0);
        $net        = $gross - $deductions;

        $data = [
            'voucher_no'         => $_POST['voucher_no'],
            'employee_id'        => intval($_POST['employee_id']),
            'payment_month'      => $_POST['payment_month'],
            'payment_year'       => intval($_POST['payment_year']),
            'payment_date'       => $_POST['payment_date'],
            'basic_salary'       => $basic,
            'housing_allowance'  => $housing,
            'transport_allowance'=> $transport,
            'other_allowance'    => $other,
            'gross_salary'       => $gross,
            'deductions'         => $deductions,
            'deduction_reason'   => trim($_POST['deduction_reason'] ?? ''),
            'net_salary'         => $net,
            'payment_mode'       => $_POST['payment_mode'],
            'notes'              => trim($_POST['notes'] ?? ''),
            'created_by'         => $_SESSION['user_id'],
        ];

        $id = $model->create($data);
        if ($id) {
            $this->redirect('salary/show/' . $id);
        } else {
            $this->redirect('salary/create?error=1');
        }
    }

    public function show($id) {
        require_once 'app/models/SalaryPaymentModel.php';
        require_once 'app/models/SettingsModel.php';
        $payment  = (new SalaryPaymentModel())->findWithEmployee($id);
        $settings = (new SettingsModel())->getAllSettings();
        if (!$payment) $this->redirect('salary');

        $this->view('salary/voucher', [
            'payment'  => $payment,
            'settings' => $settings,
            'title'    => 'Salary Voucher ' . $payment['voucher_no']
        ]);
    }

    public function delete($id) {
        require_once 'app/models/SalaryPaymentModel.php';
        (new SalaryPaymentModel())->delete($id);
        $this->redirect('salary');
    }
}
