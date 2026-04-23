<?php
class ExpensesController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/ExpenseModel.php';
        $expenseModel = new ExpenseModel();
        
        $month = $_GET['month'] ?? date('Y-m');
        $date = $_GET['date'] ?? null;
        $showAll = isset($_GET['show_all']);

        if ($showAll) {
             $expenses = $expenseModel->filter(null, null);
             $filterLabel = "All Time Expenses";
             $month = ''; // Clear for view
             $date = '';
        } elseif (!empty($date)) {
            $expenses = $expenseModel->filter(null, $date);
            $filterLabel = "Date: " . $date;
            $month = ''; // Clear month if date selected
        } elseif (!empty($month)) {
            $expenses = $expenseModel->filter($month, null);
            $filterLabel = "Month: " . date('F Y', strtotime($month));
        } else {
            // Fallback (shouldn't really happen with current logic unless manually manip URL)
            $expenses = $expenseModel->filter(date('Y-m'), null);
            $filterLabel = "Current Month";
        }
        
        require_once 'app/models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();
        
        $this->view('expenses/index', [
            'expenses' => $expenses, 
            'title' => 'Expense Management',
            'current_month' => $month,
            'current_date' => $date,
            'filter_label' => $filterLabel,
            'currency_symbol' => $settings['currency_symbol'] ?? '$'
        ]);
    }

    public function create() {
        $this->view('expenses/create', ['title' => 'Add New Expense']);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/ExpenseModel.php';
            $expenseModel = new ExpenseModel();

            $data = [
                'title' => $_POST['title'],
                'category' => $_POST['category'],
                'amount' => $_POST['amount'],
                'expense_date' => $_POST['expense_date'],
                'payment_mode' => $_POST['payment_mode'],
                'description' => $_POST['description'],
                'created_by' => $_SESSION['user_id']
            ];

            if ($expenseModel->create($data)) {
                $this->redirect('expenses');
            } else {
                echo "Error creating expense"; // Should execute view with error ideally
            }
        }
    }

    public function edit($id) {
        require_once 'app/models/ExpenseModel.php';
        $expenseModel = new ExpenseModel();
        $expense = $expenseModel->find($id);

        if (!$expense) {
            $this->redirect('expenses');
        }

        $this->view('expenses/edit', ['expense' => $expense, 'title' => 'Edit Expense']);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'app/models/ExpenseModel.php';
            $expenseModel = new ExpenseModel();

            $data = [
                'title' => $_POST['title'],
                'category' => $_POST['category'],
                'amount' => $_POST['amount'],
                'expense_date' => $_POST['expense_date'],
                'payment_mode' => $_POST['payment_mode'],
                'description' => $_POST['description']
            ];

            if ($expenseModel->update($id, $data)) {
                $this->redirect('expenses');
            } else {
                echo "Error updating expense";
            }
        }
    }

    public function delete($id) {
        require_once 'app/models/ExpenseModel.php';
        $expenseModel = new ExpenseModel();
        $expenseModel->delete($id);
        $this->redirect('expenses');
    }
}
