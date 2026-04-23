<?php
class CertificatesController extends Controller {

    public function __construct() {
        // public verify route handled via separate method; require login for everything else
        $action = $_GET['url'] ?? '';
        if (strpos($action, 'certificates/verify') !== 0) {
            $this->requireLogin();
        }
    }

    public function index() {
        require_once 'app/models/CertificateModel.php';
        $model = new CertificateModel();
        $this->view('certificates/index', [
            'certificates' => $model->getAll(),
            'title'        => 'Certificates'
        ]);
    }

    public function create() {
        require_once 'app/models/CertificateModel.php';
        $model = new CertificateModel();
        $this->view('certificates/create', [
            'certificate_no' => $model->nextCertificateNo(),
            'types'          => ['Experience', 'Internship', 'Completion', 'Training', 'Appreciation', 'Achievement', 'Participation'],
            'title'          => 'Create Certificate'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/CertificateModel.php';
        $model = new CertificateModel();

        $name = trim($_POST['candidate_name'] ?? '');
        if ($name === '') $this->redirect('certificates/create?error=name');

        $slug = $model->generateSlug($name);

        $data = [
            'certificate_no'   => $_POST['certificate_no'],
            'certificate_slug' => $slug,
            'certificate_type' => trim($_POST['certificate_type'] ?? 'Experience'),
            'candidate_name'   => $name,
            'designation'      => trim($_POST['designation'] ?? ''),
            'subject'          => trim($_POST['subject'] ?? ''),
            'duration_from'    => $_POST['duration_from'] ?: null,
            'duration_to'      => $_POST['duration_to'] ?: null,
            'description'      => trim($_POST['description'] ?? ''),
            'issue_date'       => $_POST['issue_date'] ?: date('Y-m-d'),
            'issued_by'        => trim($_POST['issued_by'] ?? ''),
            'issued_by_title'  => trim($_POST['issued_by_title'] ?? ''),
            'created_by'       => $_SESSION['user_id'] ?? null,
        ];

        $id = $model->create($data);
        if ($id) {
            $this->redirect('certificates/show/' . $id);
        } else {
            $this->redirect('certificates/create?error=1');
        }
    }

    public function show($id) {
        require_once 'app/models/CertificateModel.php';
        require_once 'app/models/SettingsModel.php';
        $cert = (new CertificateModel())->find($id);
        if (!$cert) $this->redirect('certificates');
        $settings = (new SettingsModel())->getAllSettings();

        $this->view('certificates/view', [
            'cert'     => $cert,
            'settings' => $settings,
            'title'    => 'Certificate ' . $cert['certificate_no']
        ]);
    }

    public function edit($id) {
        require_once 'app/models/CertificateModel.php';
        $cert = (new CertificateModel())->find($id);
        if (!$cert) $this->redirect('certificates');

        $this->view('certificates/edit', [
            'cert'  => $cert,
            'types' => ['Experience', 'Internship', 'Completion', 'Training', 'Appreciation', 'Achievement', 'Participation'],
            'title' => 'Edit Certificate'
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        require_once 'app/models/CertificateModel.php';
        $model = new CertificateModel();

        $cert = $model->find($id);
        if (!$cert) $this->redirect('certificates');

        $name = trim($_POST['candidate_name'] ?? '');
        if ($name === '') $this->redirect('certificates/edit/' . $id . '?error=name');

        // Regenerate slug only if name changed
        $slug = $cert['certificate_slug'];
        if (strtolower($name) !== strtolower($cert['candidate_name'])) {
            $slug = $model->generateSlugExcluding($name, $id);
        }

        $data = [
            'id'               => $id,
            'certificate_no'   => trim($_POST['certificate_no']),
            'certificate_slug' => $slug,
            'certificate_type' => trim($_POST['certificate_type'] ?? 'Experience'),
            'candidate_name'   => $name,
            'designation'      => trim($_POST['designation'] ?? ''),
            'subject'          => trim($_POST['subject'] ?? ''),
            'duration_from'    => $_POST['duration_from'] ?: null,
            'duration_to'      => $_POST['duration_to'] ?: null,
            'description'      => trim($_POST['description'] ?? ''),
            'issue_date'       => $_POST['issue_date'] ?: date('Y-m-d'),
            'issued_by'        => trim($_POST['issued_by'] ?? ''),
            'issued_by_title'  => trim($_POST['issued_by_title'] ?? ''),
        ];

        $model->update($data);
        $this->redirect('certificates/show/' . $id . '?success=updated');
    }

    public function verify($slug = null) {
        require_once 'app/models/CertificateModel.php';
        require_once 'app/models/SettingsModel.php';
        $cert = $slug ? (new CertificateModel())->findBySlug($slug) : null;
        $settings = (new SettingsModel())->getAllSettings();

        if (!$cert) {
            // render simple not-found page without layout
            echo '<!doctype html><html><head><title>Certificate Not Found</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
                <body class="bg-light"><div class="container py-5 text-center"><h1 class="mb-3">Certificate Not Found</h1>
                <p class="text-muted">The certificate slug <strong>' . htmlspecialchars($slug ?? '') . '</strong> is invalid or has been revoked.</p>
                </div></body></html>';
            return;
        }

        // public verify render without admin layout
        require_once 'app/views/certificates/verify.php';
    }

    public function delete($id) {
        require_once 'app/models/CertificateModel.php';
        (new CertificateModel())->delete($id);
        $this->redirect('certificates');
    }
}
