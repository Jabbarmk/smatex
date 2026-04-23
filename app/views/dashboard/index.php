<style>
    .card-creative {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        position: relative;
        overflow: hidden;
    }
    .card-creative:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card-creative::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.1) 100%);
        opacity: 0.1;
        pointer-events: none;
    }
    .metric-icon {
        font-size: 2.5rem;
        opacity: 0.2;
        position: absolute;
        right: 20px;
        top: 20px;
    }
    .metric-value {
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .bg-gradient-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
    .bg-gradient-warning { background: linear-gradient(135deg, #fce38a 0%, #f38181 100%); color: white; }
    .bg-gradient-danger  { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); color: white; }
    .bg-gradient-info    { background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%); color: white; }
    .text-success-creative { color: #28a745 !important; }

    /* Report section styles */
    .report-section { border-radius: 12px; overflow: hidden; }
    .report-table th { background: #f8f9fa; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; }
    .report-table td { vertical-align: middle; font-size: 0.88rem; }
    .report-table tbody tr:hover { background: #f0f4ff; }
    .badge-paid    { background: #d1fae5; color: #065f46; }
    .badge-unpaid  { background: #fee2e2; color: #991b1b; }
    .badge-partial { background: #fef9c3; color: #713f12; }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .section-header h5 {
        font-weight: 700;
        margin: 0;
        color: #1e2a3a;
    }
</style>

<!-- ===== EXISTING METRIC CARDS ROW 1 ===== -->
<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <!-- Total Leads -->
    <div class="col">
        <div class="card card-creative shadow-sm h-100 bg-white">
            <div class="card-body">
                <i class="fas fa-users metric-icon text-primary"></i>
                <h6 class="text-uppercase text-muted fw-bold mb-2 small">Total Leads</h6>
                <h2 class="metric-value text-primary mb-0"><?= $total_leads ?></h2>
                <small class="text-muted"><i class="fas fa-chart-line"></i> Pipeline Growth</small>
            </div>
        </div>
    </div>
    <!-- Won Deals -->
    <div class="col">
        <div class="card card-creative shadow-sm h-100 bg-white">
            <div class="card-body">
                <i class="fas fa-trophy metric-icon text-success"></i>
                <h6 class="text-uppercase text-muted fw-bold mb-2 small">Won Deals</h6>
                <h2 class="metric-value text-success mb-0"><?= $won_leads ?></h2>
                <small class="text-muted"><i class="fas fa-check-circle"></i> Conversions</small>
            </div>
        </div>
    </div>
    <!-- Expected Value -->
    <div class="col">
        <div class="card card-creative shadow-sm h-100 bg-white">
            <div class="card-body">
                <i class="fas fa-funnel-dollar metric-icon text-info"></i>
                <h6 class="text-uppercase text-muted fw-bold mb-2 small">Pipeline Value</h6>
                <h2 class="metric-value text-info mb-0"><?= formatMoney($expected_value) ?></h2>
                <small class="text-muted"><i class="fas fa-clock"></i> Projected</small>
            </div>
        </div>
    </div>
</div>

<!-- ===== EXISTING METRIC CARDS ROW 2 ===== -->
<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <!-- Revenue -->
    <div class="col">
        <div class="card card-creative shadow h-100 bg-gradient-primary text-white border-0">
            <div class="card-body">
                <i class="fas fa-wallet metric-icon text-white"></i>
                <h6 class="text-uppercase fw-bold mb-2 small opacity-75">Revenue Received</h6>
                <h2 class="metric-value mb-0"><?= formatMoney($revenue) ?></h2>
                <small class="opacity-75">Actual Income</small>
            </div>
        </div>
    </div>
    <!-- Outstanding Balance -->
    <div class="col">
        <div class="card card-creative shadow h-100 bg-gradient-danger text-white border-0">
            <div class="card-body">
                <i class="fas fa-exclamation-circle metric-icon text-white"></i>
                <h6 class="text-uppercase fw-bold mb-2 small opacity-75">Outstanding Balance</h6>
                <h2 class="metric-value mb-0"><?= formatMoney($outstanding_balance) ?></h2>
                <small class="opacity-75">Pending Payments</small>
            </div>
        </div>
    </div>
    <!-- Expenses -->
    <div class="col">
        <div class="card card-creative shadow h-100 bg-white border-0">
            <div class="card-body">
                <i class="fas fa-receipt metric-icon text-success"></i>
                <h6 class="text-uppercase text-muted fw-bold mb-2 small">Total Expenses</h6>
                <h2 class="metric-value text-success mb-0"><?= formatMoney($expenses) ?></h2>
                <small class="text-muted">Operational Costs</small>
            </div>
        </div>
    </div>
</div>

<!-- ===== CHARTS ===== -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
             <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h6 class="fw-bold mb-0 text-dark">Sales Performance</h6>
                 <span class="badge bg-light text-primary">Last 6 Months</span>
             </div>
             <div class="card-body px-4 pb-4">
                 <canvas id="salesChart" height="100"></canvas>
             </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
             <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                 <h6 class="fw-bold mb-0 text-dark">Lead Status</h6>
             </div>
             <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                 <div style="height: 250px; width: 100%;">
                    <canvas id="leadChart"></canvas>
                 </div>
             </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
             <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                 <h6 class="fw-bold mb-0 text-dark">Expense Breakdown</h6>
             </div>
             <div class="card-body px-4 pb-4">
                 <canvas id="expenseChart" height="200"></canvas>
             </div>
        </div>
    </div>
</div>

<!-- =====================================================
     REPORTS SECTION
     ===================================================== -->

<!-- ===== INVOICE SUMMARY CARDS ===== -->
<div class="section-header mb-3">
    <h5><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Invoice Summary</h5>
    <a href="<?= BASE_URL ?>dashboard/exportExcel/invoices" class="btn btn-sm btn-outline-success">
        <i class="fas fa-file-excel me-1"></i> Export to Excel
    </a>
</div>
<div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
    <!-- Total Invoices -->
    <div class="col">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left: 4px solid #6366f1 !important;">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Invoices</h6>
                <h3 class="fw-bold text-dark mb-0"><?= $invoice_report['total_count'] ?? 0 ?></h3>
                <p class="text-muted small mb-0"><?= formatMoney($invoice_report['total_amount'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <!-- Paid -->
    <div class="col">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left: 4px solid #10b981 !important;">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold mb-1">Paid</h6>
                <h3 class="fw-bold text-success mb-0"><?= $invoice_report['paid_count'] ?? 0 ?></h3>
                <p class="text-success small mb-0"><?= formatMoney($invoice_report['paid_amount'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <!-- Unpaid -->
    <div class="col">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left: 4px solid #ef4444 !important;">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold mb-1">Unpaid</h6>
                <h3 class="fw-bold text-danger mb-0"><?= $invoice_report['unpaid_count'] ?? 0 ?></h3>
                <p class="text-danger small mb-0"><?= formatMoney($invoice_report['unpaid_amount'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <!-- Partial -->
    <div class="col">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left: 4px solid #f59e0b !important;">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase fw-bold mb-1">Partial</h6>
                <h3 class="fw-bold text-warning mb-0"><?= $invoice_report['partial_count'] ?? 0 ?></h3>
                <p class="text-warning small mb-0"><?= formatMoney($invoice_report['partial_amount'] ?? 0) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- ===== SALESMEN-WISE REPORT ===== -->
<div class="card border-0 shadow-sm mb-5 report-section">
    <div class="card-header bg-white pt-4 px-4 border-bottom">
        <div class="section-header">
            <h5><i class="fas fa-user-tie me-2 text-purple" style="color:#764ba2"></i>Salesmen-Wise Report</h5>
            <a href="<?= BASE_URL ?>dashboard/exportExcel/salesman" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export to Excel
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table report-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="px-4 py-3">Salesman</th>
                        <th class="py-3">Role</th>
                        <th class="py-3 text-center">Total Leads</th>
                        <th class="py-3 text-center">Won Leads</th>
                        <th class="py-3 text-center">Invoices</th>
                        <th class="py-3 text-end">Paid (<?= $currency_symbol ?>)</th>
                        <th class="py-3 text-end">Unpaid (<?= $currency_symbol ?>)</th>
                        <th class="py-3 text-end">Partial (<?= $currency_symbol ?>)</th>
                        <th class="py-3 text-end pe-4">Total (<?= $currency_symbol ?>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($salesman_report)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No data available</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($salesman_report as $row): ?>
                    <tr>
                        <td class="px-4 fw-semibold">
                            <i class="fas fa-user-circle me-2 text-primary opacity-75"></i>
                            <?= htmlspecialchars($row['salesman_name']) ?>
                        </td>
                        <td><span class="badge bg-light text-secondary"><?= htmlspecialchars($row['role']) ?></span></td>
                        <td class="text-center"><?= $row['total_leads'] ?></td>
                        <td class="text-center">
                            <span class="badge badge-paid px-2 py-1"><?= $row['won_leads'] ?></span>
                        </td>
                        <td class="text-center"><?= $row['total_invoices'] ?></td>
                        <td class="text-end text-success fw-semibold"><?= formatMoney($row['paid_amount']) ?></td>
                        <td class="text-end text-danger"><?= formatMoney($row['unpaid_amount']) ?></td>
                        <td class="text-end text-warning"><?= formatMoney($row['partial_amount']) ?></td>
                        <td class="text-end pe-4 fw-bold"><?= formatMoney($row['total_invoiced']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td class="px-4 py-3" colspan="4">Totals</td>
                        <td class="text-center"><?= array_sum(array_column($salesman_report, 'total_invoices')) ?></td>
                        <td class="text-end text-success"><?= formatMoney(array_sum(array_column($salesman_report, 'paid_amount'))) ?></td>
                        <td class="text-end text-danger"><?= formatMoney(array_sum(array_column($salesman_report, 'unpaid_amount'))) ?></td>
                        <td class="text-end text-warning"><?= formatMoney(array_sum(array_column($salesman_report, 'partial_amount'))) ?></td>
                        <td class="text-end pe-4"><?= formatMoney(array_sum(array_column($salesman_report, 'total_invoiced'))) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- ===== TOTAL LEADS BY SALESMAN ===== -->
<div class="card border-0 shadow-sm mb-5 report-section">
    <div class="card-header bg-white pt-4 px-4 border-bottom">
        <div class="section-header">
            <h5><i class="fas fa-users me-2 text-info"></i>Total Leads Report (by Salesman)</h5>
            <a href="<?= BASE_URL ?>dashboard/exportExcel/leads" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export to Excel
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table report-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="px-4 py-3">Salesman</th>
                        <th class="py-3 text-center">New</th>
                        <th class="py-3 text-center">Contacted</th>
                        <th class="py-3 text-center">Qualified</th>
                        <th class="py-3 text-center">Proposal Sent</th>
                        <th class="py-3 text-center">Won</th>
                        <th class="py-3 text-center">Lost</th>
                        <th class="py-3 text-center">Total</th>
                        <th class="py-3 text-end pe-4">Expected Value (<?= $currency_symbol ?>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leads_report)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No data available</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($leads_report as $row): ?>
                    <tr>
                        <td class="px-4 fw-semibold">
                            <i class="fas fa-user-circle me-2 text-info opacity-75"></i>
                            <?= htmlspecialchars($row['salesman_name']) ?>
                        </td>
                        <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary"><?= $row['new_leads'] ?></span></td>
                        <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info"><?= $row['contacted'] ?></span></td>
                        <td class="text-center"><span class="badge bg-warning bg-opacity-10 text-warning"><?= $row['qualified'] ?></span></td>
                        <td class="text-center"><span class="badge bg-secondary bg-opacity-10 text-secondary"><?= $row['proposal_sent'] ?></span></td>
                        <td class="text-center"><span class="badge badge-paid px-2"><?= $row['won'] ?></span></td>
                        <td class="text-center"><span class="badge badge-unpaid px-2"><?= $row['lost'] ?></span></td>
                        <td class="text-center fw-bold"><?= $row['total_leads'] ?></td>
                        <td class="text-end pe-4 text-success"><?= formatMoney($row['total_expected_value']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td class="px-4 py-3">Totals</td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'new_leads')) ?></td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'contacted')) ?></td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'qualified')) ?></td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'proposal_sent')) ?></td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'won')) ?></td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'lost')) ?></td>
                        <td class="text-center"><?= array_sum(array_column($leads_report, 'total_leads')) ?></td>
                        <td class="text-end pe-4 text-success"><?= formatMoney(array_sum(array_column($leads_report, 'total_expected_value'))) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global Chart Defaults
    Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
    Chart.defaults.color = '#6c757d';

    // Sales Chart
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    
    // Gradient for Sales Chart
    const gradientSales = ctxSales.createLinearGradient(0, 0, 0, 400);
    gradientSales.addColorStop(0, 'rgba(98, 0, 234, 0.2)');
    gradientSales.addColorStop(1, 'rgba(98, 0, 234, 0)');

    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: <?= $salesLabels ?>,
            datasets: [{
                label: 'Revenue (<?= $currency_symbol ?>)',
                data: <?= $salesRevenue ?>,
                borderColor: '#6200ea',
                backgroundColor: gradientSales,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6200ea',
                pointHoverBackgroundColor: '#6200ea',
                pointHoverBorderColor: '#fff',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#f3f4f6' },
                    ticks: { callback: function(value) { return '<?= $currency_symbol ?>' + value; } }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Lead Chart (Doughnut)
    const ctxLead = document.getElementById('leadChart').getContext('2d');
    new Chart(ctxLead, {
        type: 'doughnut',
        data: {
            labels: <?= $leadStatusLabels ?>,
            datasets: [{
                data: <?= $leadStatusCounts ?>,
                backgroundColor: [
                    '#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#6366f1'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            },
            cutout: '70%'
        }
    });

    // Expense Chart (Bar)
    const ctxExpense = document.getElementById('expenseChart').getContext('2d');
    new Chart(ctxExpense, {
        type: 'bar',
        data: {
            labels: <?= $expenseLabels ?>,
            datasets: [{
                label: 'Expenses (<?= $currency_symbol ?>)',
                data: <?= $expenseValues ?>,
                backgroundColor: '#10b981',
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 2], color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>

<!-- ===== SERVICE / ITEM-WISE INVOICE SUMMARY ===== -->
<?php
// Pre-compute grand totals for the service item table footer
$svc_total_invoiced = array_sum(array_column($service_item_report, 'total_amount'));
$svc_total_paid     = array_sum(array_column($service_item_report, 'paid_amount'));
$svc_total_unpaid   = array_sum(array_column($service_item_report, 'unpaid_amount'));
$svc_total_partial  = array_sum(array_column($service_item_report, 'partial_amount'));
$svc_total_inv_cnt  = array_sum(array_column($service_item_report, 'total_invoices'));

// Service icon mapping (fallback to generic)
$svcIconMap = [
    'default'            => ['icon' => 'fa-cogs',            'color' => '#6366f1'],
    'app'                => ['icon' => 'fa-mobile-alt',      'color' => '#3b82f6'],
    'registration'       => ['icon' => 'fa-file-signature',  'color' => '#8b5cf6'],
    'website'            => ['icon' => 'fa-globe',           'color' => '#06b6d4'],
    'design'             => ['icon' => 'fa-palette',         'color' => '#ec4899'],
    'maintenance'        => ['icon' => 'fa-tools',           'color' => '#f59e0b'],
    'hosting'            => ['icon' => 'fa-server',          'color' => '#10b981'],
    'seo'                => ['icon' => 'fa-search',          'color' => '#ef4444'],
    'consultation'       => ['icon' => 'fa-comments',        'color' => '#14b8a6'],
];
function getSvcIcon($name, $map) {
    $lower = strtolower($name);
    foreach ($map as $key => $val) {
        if ($key !== 'default' && strpos($lower, $key) !== false) return $val;
    }
    return $map['default'];
}
?>

<div class="card border-0 shadow-sm mb-5 mt-2" style="border-radius:14px; overflow:hidden;">
    <div class="card-header bg-white pt-4 px-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold mb-0">
                <i class="fas fa-layer-group me-2 text-indigo" style="color:#6366f1;"></i>
                Service / Item-wise Invoice Summary
            </h6>
            <small class="text-muted">Breakdown of invoiced amounts by service type</small>
        </div>
        <a href="<?= BASE_URL ?>dashboard/exportExcel/service_items" class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel me-1"></i>Export
        </a>
    </div>

    <!-- Summary mini-cards -->
    <div class="row g-0 border-bottom">
        <div class="col-6 col-md-3 p-3 border-end text-center">
            <div class="fw-bold text-dark"><?= count($service_item_report) ?></div>
            <small class="text-muted">Service Types</small>
        </div>
        <div class="col-6 col-md-3 p-3 border-end text-center">
            <div class="fw-bold text-success"><?= formatMoney($svc_total_paid) ?></div>
            <small class="text-muted">Total Paid</small>
        </div>
        <div class="col-6 col-md-3 p-3 border-end text-center">
            <div class="fw-bold text-danger"><?= formatMoney($svc_total_unpaid) ?></div>
            <small class="text-muted">Total Unpaid</small>
        </div>
        <div class="col-6 col-md-3 p-3 text-center">
            <div class="fw-bold"><?= formatMoney($svc_total_invoiced) ?></div>
            <small class="text-muted">Grand Total</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table report-table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="px-4 py-3">Service / Item</th>
                    <th class="py-3 text-center">Invoices</th>
                    <th class="py-3 text-end">Paid (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end">Unpaid (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end">Partial (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end pe-4">Total (<?= $currency_symbol ?>)</th>
                    <th class="py-3" style="min-width:130px;">Collection</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($service_item_report)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No invoice items found</td></tr>
            <?php else: ?>
            <?php foreach ($service_item_report as $svc):
                $icon = getSvcIcon($svc['service_name'], $svcIconMap);
                $rate = $svc['total_amount'] > 0
                    ? round(($svc['paid_amount'] / $svc['total_amount']) * 100) : 0;
            ?>
                <tr>
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:38px;height:38px;border-radius:10px;background:<?= $icon['color'] ?>22;
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas <?= $icon['icon'] ?>" style="color:<?= $icon['color'] ?>;font-size:.95rem;"></i>
                            </div>
                            <span class="fw-semibold"><?= htmlspecialchars($svc['service_name']) ?></span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark"><?= $svc['total_invoices'] ?></span>
                    </td>
                    <td class="text-end">
                        <span class="text-success fw-semibold"><?= formatMoney($svc['paid_amount']) ?></span>
                        <?php if ($svc['paid_count'] > 0): ?>
                        <br><small class="text-muted"><?= $svc['paid_count'] ?> inv</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <span class="text-danger"><?= formatMoney($svc['unpaid_amount']) ?></span>
                        <?php if ($svc['unpaid_count'] > 0): ?>
                        <br><small class="text-muted"><?= $svc['unpaid_count'] ?> inv</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <span class="text-warning"><?= formatMoney($svc['partial_amount']) ?></span>
                        <?php if ($svc['partial_count'] > 0): ?>
                        <br><small class="text-muted"><?= $svc['partial_count'] ?> inv</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4 fw-bold"><?= formatMoney($svc['total_amount']) ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1">
                                <div class="progress" style="height:6px;border-radius:3px;">
                                    <div class="progress-bar bg-success" style="width:<?= $rate ?>%;"></div>
                                </div>
                            </div>
                            <span class="small fw-semibold" style="min-width:32px;"><?= $rate ?>%</span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td class="px-4 py-3">Grand Total</td>
                    <td class="text-center"><?= $svc_total_inv_cnt ?></td>
                    <td class="text-end text-success"><?= formatMoney($svc_total_paid) ?></td>
                    <td class="text-end text-danger"><?= formatMoney($svc_total_unpaid) ?></td>
                    <td class="text-end text-warning"><?= formatMoney($svc_total_partial) ?></td>
                    <td class="text-end pe-4"><?= formatMoney($svc_total_invoiced) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
