<?php
class LeadsController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/LeadModel.php';
        $leadModel = new LeadModel();
        $leads = $leadModel->getAllWithSalesManager();
        
        $this->view('leads/index', ['leads' => $leads, 'title' => 'Leads Management']);
    }

    public function create() {
        // Fetch users for dropdown
        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $users = $userModel->all(); // Should filter by sales role ideally

        $this->view('leads/create', ['users' => $users, 'title' => 'Create New Lead']);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/LeadModel.php';
            $leadModel = new LeadModel();

            $data = [
                'lead_name' => $_POST['lead_name'],
                'company_name' => $_POST['company_name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'sales_manager_id' => $_POST['sales_manager_id'],
                'emirates' => $_POST['emirates'],
                'status' => 'New',
                'expected_value' => $_POST['expected_value'] ?? 0,
                'notes' => $_POST['notes']
            ];

            if ($leadModel->create($data)) {
                $this->redirect('leads');
            } else {
                echo "Error creating lead";
            }
        }
    }

    public function edit($id) {
        require_once 'app/models/LeadModel.php';
        require_once 'app/models/UserModel.php';
        
        $leadModel = new LeadModel();
        $userModel = new UserModel();
        
        $lead = $leadModel->find($id);
        $users = $userModel->all();

        if (!$lead) {
            $this->redirect('leads');
        }

        $this->view('leads/edit', ['lead' => $lead, 'users' => $users, 'title' => 'Edit Lead']);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/LeadModel.php';
            $leadModel = new LeadModel();

            $data = [
                'lead_name' => $_POST['lead_name'],
                'company_name' => $_POST['company_name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'sales_manager_id' => $_POST['sales_manager_id'],
                'emirates' => $_POST['emirates'],
                'status' => $_POST['status'],
                'expected_value' => $_POST['expected_value'],
                'notes' => $_POST['notes']
            ];

            if ($leadModel->update($id, $data)) {
                $this->redirect('leads');
            } else {
                echo "Error updating lead";
            }
        }
    }

    public function delete($id) {
        require_once 'app/models/LeadModel.php';
        $leadModel = new LeadModel();
        $leadModel->delete($id);
        $this->redirect('leads');
    }
}
