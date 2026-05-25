<?php
class ContractsController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/ContractModel.php';
        $model = new ContractModel();
        $contracts = $model->getAll();
        $this->view('contracts/index', ['contracts' => $contracts, 'title' => 'Contracts']);
    }

    public function create() {
        $contractNo = 'CONT-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));
        $this->view('contracts/create', [
            'contract_no' => $contractNo,
            'title'       => 'Create Contract',
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('contracts');
        }
        require_once 'app/models/ContractModel.php';
        $model = new ContractModel();

        $data = [
            'contract_no'                => $_POST['contract_no'],
            'title'                      => $_POST['title'],
            'contract_type'              => $_POST['contract_type'],
            'first_party_name'           => $_POST['first_party_name']           ?? '',
            'first_party_address'        => $_POST['first_party_address']        ?? '',
            'first_party_phone'          => $_POST['first_party_phone']          ?? '',
            'first_party_email'          => $_POST['first_party_email']          ?? '',
            'first_party_representative' => $_POST['first_party_representative'] ?? '',
            'first_party_designation'    => $_POST['first_party_designation']    ?? '',
            'second_party_name'          => $_POST['second_party_name']          ?? '',
            'second_party_address'       => $_POST['second_party_address']       ?? '',
            'second_party_phone'         => $_POST['second_party_phone']         ?? '',
            'second_party_email'         => $_POST['second_party_email']         ?? '',
            'second_party_representative'=> $_POST['second_party_representative']?? '',
            'second_party_designation'   => $_POST['second_party_designation']   ?? '',
            'start_date'                 => $_POST['start_date']                 ?: null,
            'end_date'                   => $_POST['end_date']                   ?: null,
            'value'                      => floatval($_POST['value']             ?? 0),
            'contents'                   => $_POST['contents']                   ?? '',
            'terms_conditions'           => $_POST['terms_conditions']           ?? '',
            'notes'                      => $_POST['notes']                      ?? '',
            'status'                     => $_POST['status']                     ?? 'Draft',
            'created_by'                 => $_SESSION['user_id'],
        ];

        $id = $model->create($data);
        if ($id) {
            $this->redirect('contracts/show/' . $id);
        } else {
            echo 'Error creating contract.';
        }
    }

    public function show($id) {
        require_once 'app/models/ContractModel.php';
        require_once 'app/models/SettingsModel.php';
        $model    = new ContractModel();
        $settings = (new SettingsModel())->getAllSettings();
        $contract = $model->find($id);
        if (!$contract) $this->redirect('contracts');
        $this->view('contracts/view', [
            'contract' => $contract,
            'settings' => $settings,
            'title'    => 'Contract: ' . $contract['contract_no'],
        ]);
    }

    public function edit($id) {
        require_once 'app/models/ContractModel.php';
        $model    = new ContractModel();
        $contract = $model->find($id);
        if (!$contract) $this->redirect('contracts');
        $this->view('contracts/edit', [
            'contract' => $contract,
            'title'    => 'Edit Contract',
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('contracts');
        }
        require_once 'app/models/ContractModel.php';
        $model = new ContractModel();

        $data = [
            'title'                      => $_POST['title'],
            'contract_type'              => $_POST['contract_type'],
            'first_party_name'           => $_POST['first_party_name']           ?? '',
            'first_party_address'        => $_POST['first_party_address']        ?? '',
            'first_party_phone'          => $_POST['first_party_phone']          ?? '',
            'first_party_email'          => $_POST['first_party_email']          ?? '',
            'first_party_representative' => $_POST['first_party_representative'] ?? '',
            'first_party_designation'    => $_POST['first_party_designation']    ?? '',
            'second_party_name'          => $_POST['second_party_name']          ?? '',
            'second_party_address'       => $_POST['second_party_address']       ?? '',
            'second_party_phone'         => $_POST['second_party_phone']         ?? '',
            'second_party_email'         => $_POST['second_party_email']         ?? '',
            'second_party_representative'=> $_POST['second_party_representative']?? '',
            'second_party_designation'   => $_POST['second_party_designation']   ?? '',
            'start_date'                 => $_POST['start_date']                 ?: null,
            'end_date'                   => $_POST['end_date']                   ?: null,
            'value'                      => floatval($_POST['value']             ?? 0),
            'contents'                   => $_POST['contents']                   ?? '',
            'terms_conditions'           => $_POST['terms_conditions']           ?? '',
            'notes'                      => $_POST['notes']                      ?? '',
            'status'                     => $_POST['status']                     ?? 'Draft',
        ];

        if ($model->update($id, $data)) {
            $this->redirect('contracts/show/' . $id);
        } else {
            echo 'Error updating contract.';
        }
    }

    public function delete($id) {
        require_once 'app/models/ContractModel.php';
        (new ContractModel())->delete($id);
        $this->redirect('contracts');
    }
}
