<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>public/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php require_once 'app/helpers/currency.php'; ?>

<?php if (isset($_SESSION['user_id'])): ?>
<?php
// Ensure settings are available for the layout
if (!isset($settings) || !isset($settings['company_logo'])) {
    if (file_exists('app/models/SettingsModel.php')) {
        require_once 'app/models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();
    }
}
?>
<nav class="sidebar">
    <div class="sidebar-logo text-center p-3">
        <?php if (!empty($settings['company_logo']) && file_exists('public/uploads/' . $settings['company_logo'])): ?>
            <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" class="img-fluid mb-2" style="max-height: 50px;">
            <div class="text-black fw-bold h4 ls-1" style="letter-spacing: 1px;">Smatflix</div>
        <?php else: ?>
            <h4 class="text-white fw-bold mb-0 ls-1" style="letter-spacing: 1px;"><i class="fas fa-cube me-2"></i>Smatflix</h4>
        <?php endif; ?>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="<?= BASE_URL ?>dashboard" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>leads" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'leads') !== false ? 'active' : '' ?>">
                <i class="fas fa-funnel-dollar"></i> Leads CRM
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>expenses" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'expenses') !== false ? 'active' : '' ?>">
                <i class="fas fa-wallet"></i> Expenses
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>quotations" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'quotations') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-invoice"></i> Quotations
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>invoices" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'invoices') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-invoice-dollar"></i> Invoices
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>receipts" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'receipts') !== false ? 'active' : '' ?>">
                <i class="fas fa-receipt"></i> Receipts
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>paymentvoucher" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'paymentvoucher') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-invoice-dollar"></i> Payment Vouchers
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>salesreport" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'salesreport') !== false ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i> Sales Report
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>employees" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'employees') !== false ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Employees
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>salary" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'salary') !== false ? 'active' : '' ?>">
                <i class="fas fa-money-check-alt"></i> Salary
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>certificates" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'certificates') !== false ? 'active' : '' ?>">
                <i class="fas fa-certificate"></i> Certificates
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>offerletters" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'offerletters') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-signature"></i> Offer Letters
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>statements" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'statements') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-contract"></i> Statements
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>contracts" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'contracts') !== false ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i> Contracts
            </a>
        </li>
        <?php if ($_SESSION['user_role'] == 'Super Admin'): ?>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>users" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : '' ?>">
                <i class="fas fa-users-cog"></i> User Management
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL ?>settings" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>">
                <i class="fas fa-cogs"></i> System Settings
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item mt-auto">
            <a href="<?= BASE_URL ?>auth/logout" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</nav>

<!-- Main Content Wrapper -->
<div class="sidebar-overlay"></div>
<div class="main-content">
    <!-- Topbar -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="mb-0 fw-bold"><?= $title ?? 'Dashboard' ?></h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-light rounded-circle shadow-sm" type="button" id="userMenu" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                    <li><a class="dropdown-item" href="#"><?= $_SESSION['user_name'] ?? 'User' ?></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>auth/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
