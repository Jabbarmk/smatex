<?php
class OfferlettersController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        require_once 'app/models/OfferLetterModel.php';
        $this->view('offerletters/index', [
            'offers' => (new OfferLetterModel())->getAll(),
            'title'  => 'Offer Letters'
        ]);
    }

    public function create() {
        require_once 'app/models/OfferLetterModel.php';
        require_once 'app/models/EmployeeModel.php';
        $model = new OfferLetterModel();

        $empId = intval($_GET['emp'] ?? 0);
        $prefill = [];
        if ($empId) {
            $emp = (new EmployeeModel())->find($empId);
            if ($emp) {
                $prefill = [
                    'employee_id'         => $emp['id'],
                    'candidate_name'      => $emp['full_name'],
                    'designation'         => $emp['designation'],
                    'department'          => $emp['department'],
                    'nationality'         => $emp['nationality'],
                    'joining_date'        => $emp['join_date'],
                    'basic_salary'        => $emp['basic_salary'],
                    'housing_allowance'   => $emp['housing_allowance'],
                    'transport_allowance' => $emp['transport_allowance'],
                    'other_allowance'     => $emp['other_allowance'],
                ];
            }
        }

        $this->view('offerletters/create', [
            'offer_no' => $model->nextOfferNo(),
            'prefill'  => $prefill,
            'title'    => 'Create Offer Letter'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/OfferLetterModel.php';

        $data = [
            'offer_no'            => trim($_POST['offer_no']),
            'employee_id'         => intval($_POST['employee_id'] ?? 0) ?: null,
            'candidate_name'      => trim($_POST['candidate_name']),
            'designation'         => trim($_POST['designation'] ?? ''),
            'department'          => trim($_POST['department'] ?? ''),
            'nationality'         => trim($_POST['nationality'] ?? ''),
            'offer_date'          => $_POST['offer_date'] ?: date('Y-m-d'),
            'joining_date'        => $_POST['joining_date'] ?: null,
            'basic_salary'        => floatval($_POST['basic_salary'] ?? 0),
            'housing_allowance'   => floatval($_POST['housing_allowance'] ?? 0),
            'transport_allowance' => floatval($_POST['transport_allowance'] ?? 0),
            'other_allowance'     => floatval($_POST['other_allowance'] ?? 0),
            'probation_period'    => trim($_POST['probation_period'] ?? '3 months'),
            'working_hours'       => trim($_POST['working_hours'] ?? '8 hours per day, 5 days a week'),
            'annual_leave'        => trim($_POST['annual_leave'] ?? '30 days per year'),
            'notice_period'       => trim($_POST['notice_period'] ?? '30 days'),
            'issued_by'           => trim($_POST['issued_by'] ?? ''),
            'issued_by_title'     => trim($_POST['issued_by_title'] ?? ''),
            'notes'               => trim($_POST['notes'] ?? ''),
            'created_by'          => $_SESSION['user_id'] ?? null,
        ];

        if ($data['candidate_name'] === '') {
            $this->redirect('offerletters/create?error=name');
        }

        $id = (new OfferLetterModel())->create($data);
        if ($id) {
            $this->redirect('offerletters/show/' . $id);
        } else {
            $this->redirect('offerletters/create?error=1');
        }
    }

    public function show($id) {
        require_once 'app/models/OfferLetterModel.php';
        require_once 'app/models/SettingsModel.php';
        $offer = (new OfferLetterModel())->find($id);
        if (!$offer) $this->redirect('offerletters');

        $this->view('offerletters/view', [
            'offer'    => $offer,
            'settings' => (new SettingsModel())->getAllSettings(),
            'title'    => 'Offer Letter ' . $offer['offer_no']
        ]);
    }

    public function edit($id) {
        require_once 'app/models/OfferLetterModel.php';
        $offer = (new OfferLetterModel())->find($id);
        if (!$offer) $this->redirect('offerletters');

        $this->view('offerletters/edit', [
            'offer' => $offer,
            'title' => 'Edit Offer Letter'
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/OfferLetterModel.php';

        $data = [
            'candidate_name'      => trim($_POST['candidate_name']),
            'designation'         => trim($_POST['designation'] ?? ''),
            'department'          => trim($_POST['department'] ?? ''),
            'nationality'         => trim($_POST['nationality'] ?? ''),
            'offer_date'          => $_POST['offer_date'] ?: date('Y-m-d'),
            'joining_date'        => $_POST['joining_date'] ?: null,
            'basic_salary'        => floatval($_POST['basic_salary'] ?? 0),
            'housing_allowance'   => floatval($_POST['housing_allowance'] ?? 0),
            'transport_allowance' => floatval($_POST['transport_allowance'] ?? 0),
            'other_allowance'     => floatval($_POST['other_allowance'] ?? 0),
            'probation_period'    => trim($_POST['probation_period'] ?? '3 months'),
            'working_hours'       => trim($_POST['working_hours'] ?? '8 hours per day, 5 days a week'),
            'annual_leave'        => trim($_POST['annual_leave'] ?? '30 days per year'),
            'notice_period'       => trim($_POST['notice_period'] ?? '30 days'),
            'issued_by'           => trim($_POST['issued_by'] ?? ''),
            'issued_by_title'     => trim($_POST['issued_by_title'] ?? ''),
            'notes'               => trim($_POST['notes'] ?? ''),
        ];

        (new OfferLetterModel())->update($id, $data);
        $this->redirect('offerletters/show/' . $id . '?success=updated');
    }

    public function delete($id) {
        require_once 'app/models/OfferLetterModel.php';
        (new OfferLetterModel())->delete($id);
        $this->redirect('offerletters');
    }
}
