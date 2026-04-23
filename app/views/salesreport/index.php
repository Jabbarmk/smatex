<style>
    .sr-card {
        border-radius: 14px;
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .sr-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important; }
    .summary-bar { height: 6px; border-radius: 3px; }
    .salesman-row { cursor: pointer; transition: background 0.15s; }
    .salesman-row:hover { background: #f0f4ff !important; }
    .badge-paid    { background: #d1fae5; color: #065f46; font-size:.8rem; }
    .badge-unpaid  { background: #fee2e2; color: #991b1b; font-size:.8rem; }
    .badge-partial { background: #fef9c3; color: #713f12; font-size:.8rem; }
    .rpt-th { font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; color: #6c757d; background: #f8f9fa; }
    .rpt-td { font-size: .875rem; vertical-align: middle; }
    .progress-thin { height: 5px; border-radius: 3px; }
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1rem; flex-shrink: 0;
    }
</style>

<?php
// Palette for avatars
$palette = ['#667eea','#10b981','#f59e0b','#ef4444','#6366f1','#ec4899','#14b8a6','#8b5cf6'];
$totalPaid    = array_sum(array_column($salesmen, 'paid_amount'));
$totalUnpaid  = array_sum(array_column($salesmen, 'unpaid_amount'));
$totalPartial = array_sum(array_column($salesmen, 'partial_amount'));
$grandTotal   = array_sum(array_column($salesmen, 'total_invoiced'));
?>

<!-- ===== PAGE HEADER ===== -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Sales Report</h4>
        <p class="text-muted small mb-0">All salesmen · Invoice performance overview</p>
    </div>
    <a href="<?= BASE_URL ?>salesreport/exportOverview" class="btn btn-success">
        <i class="fas fa-file-excel me-2"></i>Export All to Excel
    </a>
</div>

<!-- ===== TOP SUMMARY CARDS ===== -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card sr-card shadow-sm h-100" style="border-left:5px solid #6366f1;">
            <div class="card-body">
                <p class="text-muted small mb-1 text-uppercase fw-bold">Total Invoiced</p>
                <h4 class="fw-bold mb-0"><?= formatMoney($grand_totals['total_invoiced']) ?></h4>
                <small class="text-muted"><?= $grand_totals['total_invoices'] ?> invoices</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card sr-card shadow-sm h-100" style="border-left:5px solid #10b981;">
            <div class="card-body">
                <p class="text-muted small mb-1 text-uppercase fw-bold">Total Paid</p>
                <h4 class="fw-bold text-success mb-0"><?= formatMoney($grand_totals['paid_amount']) ?></h4>
                <small class="text-muted"><?= $grand_totals['paid_count'] ?> invoices</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card sr-card shadow-sm h-100" style="border-left:5px solid #ef4444;">
            <div class="card-body">
                <p class="text-muted small mb-1 text-uppercase fw-bold">Total Unpaid</p>
                <h4 class="fw-bold text-danger mb-0"><?= formatMoney($grand_totals['unpaid_amount']) ?></h4>
                <small class="text-muted"><?= $grand_totals['unpaid_count'] ?> invoices</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card sr-card shadow-sm h-100" style="border-left:5px solid #f59e0b;">
            <div class="card-body">
                <p class="text-muted small mb-1 text-uppercase fw-bold">Partial</p>
                <h4 class="fw-bold text-warning mb-0"><?= formatMoney($grand_totals['partial_amount']) ?></h4>
                <small class="text-muted"><?= $grand_totals['partial_count'] ?> invoices</small>
            </div>
        </div>
    </div>
</div>

<!-- ===== MAIN TABLE ===== -->
<div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
    <div class="card-header bg-white pt-4 px-4 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="fas fa-user-tie me-2 text-primary"></i>Salesmen Overview</h6>
        <span class="badge bg-primary bg-opacity-10 text-primary"><?= count($salesmen) ?> salespeople</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="rpt-th px-4 py-3">Salesman</th>
                    <th class="rpt-th py-3">Role</th>
                    <th class="rpt-th py-3 text-center">Leads</th>
                    <th class="rpt-th py-3 text-center">Invoices</th>
                    <th class="rpt-th py-3 text-end">Paid (<?= $currency_symbol ?>)</th>
                    <th class="rpt-th py-3 text-end">Unpaid (<?= $currency_symbol ?>)</th>
                    <th class="rpt-th py-3 text-end">Partial (<?= $currency_symbol ?>)</th>
                    <th class="rpt-th py-3 text-end">Total (<?= $currency_symbol ?>)</th>
                    <th class="rpt-th py-3 text-center">Collection Rate</th>
                    <th class="rpt-th py-3 text-center pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($salesmen)): ?>
                <tr><td colspan="10" class="text-center text-muted py-5">No data found</td></tr>
            <?php else: ?>
            <?php foreach ($salesmen as $i => $s):
                $color       = $palette[$i % count($palette)];
                $initials    = strtoupper(substr($s['salesman_name'], 0, 1));
                $rate        = $s['total_invoiced'] > 0 ? round(($s['paid_amount'] / $s['total_invoiced']) * 100) : 0;
            ?>
                <tr class="salesman-row" onclick="window.location='<?= BASE_URL ?>salesreport/detail/<?= $s['salesman_id'] ?>'">
                    <td class="rpt-td px-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle text-white" style="background:<?= $color ?>;"><?= $initials ?></div>
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($s['salesman_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($s['salesman_email'] ?? '') ?></small>
                            </div>
                        </div>
                    </td>
                    <td class="rpt-td"><span class="badge bg-light text-secondary"><?= htmlspecialchars($s['role']) ?></span></td>
                    <td class="rpt-td text-center">
                        <?= $s['total_leads'] ?>
                        <?php if ($s['won_leads'] > 0): ?>
                        <span class="badge badge-paid ms-1"><?= $s['won_leads'] ?> won</span>
                        <?php endif; ?>
                    </td>
                    <td class="rpt-td text-center"><?= $s['total_invoices'] ?></td>
                    <td class="rpt-td text-end text-success fw-semibold"><?= formatMoney($s['paid_amount']) ?><br><small class="text-muted"><?= $s['paid_count'] ?> inv</small></td>
                    <td class="rpt-td text-end text-danger"><?= formatMoney($s['unpaid_amount']) ?><br><small class="text-muted"><?= $s['unpaid_count'] ?> inv</small></td>
                    <td class="rpt-td text-end text-warning"><?= formatMoney($s['partial_amount']) ?><br><small class="text-muted"><?= $s['partial_count'] ?> inv</small></td>
                    <td class="rpt-td text-end fw-bold"><?= formatMoney($s['total_invoiced']) ?></td>
                    <td class="rpt-td" style="min-width:120px;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1">
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-success" style="width:<?= $rate ?>%"></div>
                                </div>
                            </div>
                            <span class="small fw-semibold text-success"><?= $rate ?>%</span>
                        </div>
                    </td>
                    <td class="rpt-td text-center pe-4" onclick="event.stopPropagation()">
                        <a href="<?= BASE_URL ?>salesreport/detail/<?= $s['salesman_id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?= BASE_URL ?>salesreport/exportDetail/<?= $s['salesman_id'] ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td class="px-4 py-3" colspan="3">Grand Totals</td>
                    <td class="text-center"><?= $grand_totals['total_invoices'] ?></td>
                    <td class="text-end text-success"><?= formatMoney($grand_totals['paid_amount']) ?></td>
                    <td class="text-end text-danger"><?= formatMoney($grand_totals['unpaid_amount']) ?></td>
                    <td class="text-end text-warning"><?= formatMoney($grand_totals['partial_amount']) ?></td>
                    <td class="text-end"><?= formatMoney($grand_totals['total_invoiced']) ?></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Tooltip: click row -->
<p class="text-muted small text-center mt-3"><i class="fas fa-info-circle me-1"></i>Click on any row to view the salesman's full client & invoice breakdown.</p>
