<?php
class EmployeesController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/EmployeeModel.php';
        $model = new EmployeeModel();
        $this->view('employees/index', [
            'employees' => $model->getAll(),
            'title' => 'Employee Master'
        ]);
    }

    public function create() {
        require_once 'app/models/EmployeeModel.php';
        $model = new EmployeeModel();
        $this->view('employees/create', [
            'employee_no' => $model->nextEmployeeNo(),
            'title' => 'Add Employee'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/EmployeeModel.php';
        $model = new EmployeeModel();

        $data = [
            'employee_no'         => $_POST['employee_no'],
            'full_name'           => trim($_POST['full_name']),
            'designation'         => trim($_POST['designation'] ?? ''),
            'department'          => trim($_POST['department'] ?? ''),
            'nationality'         => trim($_POST['nationality'] ?? ''),
            'passport_no'         => trim($_POST['passport_no'] ?? ''),
            'visa_uid'            => trim($_POST['visa_uid'] ?? ''),
            'emirates_id'         => trim($_POST['emirates_id'] ?? ''),
            'mobile'              => trim($_POST['mobile'] ?? ''),
            'email'               => trim($_POST['email'] ?? ''),
            'bank_name'           => trim($_POST['bank_name'] ?? ''),
            'bank_account'        => trim($_POST['bank_account'] ?? ''),
            'iban'                => trim($_POST['iban'] ?? ''),
            'basic_salary'        => floatval($_POST['basic_salary'] ?? 0),
            'housing_allowance'   => floatval($_POST['housing_allowance'] ?? 0),
            'transport_allowance' => floatval($_POST['transport_allowance'] ?? 0),
            'other_allowance'     => floatval($_POST['other_allowance'] ?? 0),
            'join_date'           => $_POST['join_date'] ?: null,
            'status'              => $_POST['status'] ?? 'Active',
            'notes'               => trim($_POST['notes'] ?? ''),
        ];

        if ($model->create($data)) {
            $this->redirect('employees?success=added');
        } else {
            $this->redirect('employees/create?error=1');
        }
    }

    public function edit($id) {
        require_once 'app/models/EmployeeModel.php';
        $model = new EmployeeModel();
        $employee = $model->find($id);
        if (!$employee) $this->redirect('employees');

        $this->view('employees/edit', [
            'employee' => $employee,
            'title'    => 'Edit Employee'
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/EmployeeModel.php';
        $model = new EmployeeModel();

        $data = [
            'full_name'           => trim($_POST['full_name']),
            'designation'         => trim($_POST['designation'] ?? ''),
            'department'          => trim($_POST['department'] ?? ''),
            'nationality'         => trim($_POST['nationality'] ?? ''),
            'passport_no'         => trim($_POST['passport_no'] ?? ''),
            'visa_uid'            => trim($_POST['visa_uid'] ?? ''),
            'emirates_id'         => trim($_POST['emirates_id'] ?? ''),
            'mobile'              => trim($_POST['mobile'] ?? ''),
            'email'               => trim($_POST['email'] ?? ''),
            'bank_name'           => trim($_POST['bank_name'] ?? ''),
            'bank_account'        => trim($_POST['bank_account'] ?? ''),
            'iban'                => trim($_POST['iban'] ?? ''),
            'basic_salary'        => floatval($_POST['basic_salary'] ?? 0),
            'housing_allowance'   => floatval($_POST['housing_allowance'] ?? 0),
            'transport_allowance' => floatval($_POST['transport_allowance'] ?? 0),
            'other_allowance'     => floatval($_POST['other_allowance'] ?? 0),
            'join_date'           => $_POST['join_date'] ?: null,
            'status'              => $_POST['status'] ?? 'Active',
            'notes'               => trim($_POST['notes'] ?? ''),
        ];

        $model->update($id, $data);
        $this->redirect('employees?success=updated');
    }

    public function delete($id) {
        require_once 'app/models/EmployeeModel.php';
        (new EmployeeModel())->delete($id);
        $this->redirect('employees');
    }
}
