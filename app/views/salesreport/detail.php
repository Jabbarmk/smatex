<style>
    .detail-header-card { border-radius: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .client-card { border-radius: 12px; border: 1px solid #e9ecef; margin-bottom: 1.5rem; overflow: hidden; transition: box-shadow 0.2s; }
    .client-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .client-card-header { background: #f8f9fa; padding: 1rem 1.25rem; border-bottom: 1px solid #e9ecef; }
    .client-card-header.expanded { background: #eef2ff; border-bottom-color: #c7d2fe; }
    .inv-table th { font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; color: #6c757d; background: #fff; }
    .inv-table td { font-size: .85rem; vertical-align: middle; }
    .status-paid    { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight:600; }
    .status-unpaid  { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight:600; }
    .status-partial { background: #fef9c3; color: #713f12; padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight:600; }
    .lead-badge { background: #e0f2fe; color: #0369a1; font-size: .75rem; font-weight:600; padding: 2px 8px; border-radius:10px; }
    .summary-metric { text-align:center; }
    .summary-metric h5 { font-weight:700; margin-bottom:2px; }
    .summary-metric p  { font-size:.8rem; color: rgba(255,255,255,0.75); margin:0; }
    .collapse-icon { transition: transform .2s; }
    .collapsed .collapse-icon { transform: rotate(-90deg); }
    .progress-sm { height: 6px; border-radius:3px; }
</style>

<?php
$salesman_color = '#667eea';
$initials = strtoupper(substr($salesman['name'], 0, 2));
$collection_rate = $totals['total_invoiced'] > 0
    ? round(($totals['paid_amount'] / $totals['total_invoiced']) * 100) : 0;
?>

<!-- Back button -->
<div class="mb-3">
    <a href="<?= BASE_URL ?>salesreport" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Back to Sales Report
    </a>
</div>

<!-- ===== SALESMAN SUMMARY HEADER ===== -->
<div class="detail-header-card p-4 mb-4 shadow-sm">
    <div class="row align-items-center g-4">
        <div class="col-auto">
            <div style="width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,0.2);
                        display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;">
                <?= $initials ?>
            </div>
        </div>
        <div class="col">
            <h4 class="fw-bold mb-1"><?= htmlspecialchars($salesman['name']) ?></h4>
            <div class="d-flex gap-3 flex-wrap opacity-75 small">
                <span><i class="fas fa-tag me-1"></i><?= $salesman['role'] ?></span>
                <?php if (!empty($salesman['email'])): ?>
                <span><i class="fas fa-envelope me-1"></i><?= $salesman['email'] ?></span>
                <?php endif; ?>
                <?php if (!empty($salesman['phone'])): ?>
                <span><i class="fas fa-phone me-1"></i><?= $salesman['phone'] ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-auto">
            <a href="<?= BASE_URL ?>salesreport/exportDetail/<?= $salesman['id'] ?>" class="btn btn-light btn-sm fw-semibold">
                <i class="fas fa-file-excel me-2 text-success"></i>Export to Excel
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="row g-3 mt-2 text-white">
        <div class="col-6 col-md-2 summary-metric">
            <h5><?= count($clients) ?></h5>
            <p>Clients</p>
        </div>
        <div class="col-6 col-md-2 summary-metric">
            <h5><?= $totals['total_invoices'] ?></h5>
            <p>Total Invoices</p>
        </div>
        <div class="col-6 col-md-2 summary-metric">
            <h5><?= formatMoney($totals['paid_amount']) ?></h5>
            <p>Paid (<?= $totals['paid_count'] ?>)</p>
        </div>
        <div class="col-6 col-md-2 summary-metric">
            <h5><?= formatMoney($totals['unpaid_amount']) ?></h5>
            <p>Unpaid (<?= $totals['unpaid_count'] ?>)</p>
        </div>
        <div class="col-6 col-md-2 summary-metric">
            <h5><?= formatMoney($totals['partial_amount']) ?></h5>
            <p>Partial (<?= $totals['partial_count'] ?>)</p>
        </div>
        <div class="col-6 col-md-2 summary-metric">
            <h5><?= formatMoney($totals['total_invoiced']) ?></h5>
            <p>Total</p>
        </div>
    </div>

    <!-- Collection Rate -->
    <div class="mt-3">
        <div class="d-flex justify-content-between small opacity-75 mb-1">
            <span>Collection Rate</span>
            <span><?= $collection_rate ?>%</span>
        </div>
        <div class="progress progress-sm bg-white bg-opacity-25">
            <div class="progress-bar bg-white" style="width:<?= $collection_rate ?>%;"></div>
        </div>
    </div>
</div>

<!-- ===== CLIENT CARDS ===== -->
<?php if (empty($clients)): ?>
<div class="text-center py-5 text-muted">
    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
    <p>No clients or invoices found for this salesman.</p>
</div>
<?php else: ?>

<?php foreach ($clients as $ci => $client):
    $clientRate = $client['total_invoiced'] > 0
        ? round(($client['paid_amount'] / $client['total_invoiced']) * 100) : 0;
    $collapseId = 'client_' . $client['lead_id'];
?>

<div class="client-card">
    <!-- Client Header — toggle invoices -->
    <div class="client-card-header d-flex align-items-center justify-content-between gap-2"
         data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>"
         style="cursor:pointer;" aria-expanded="true">
        <div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap">
            <!-- Avatar -->
            <div style="width:42px;height:42px;border-radius:50%;background:#667eea22;
                        display:flex;align-items:center;justify-content:center;font-weight:700;color:#667eea;font-size:1rem;flex-shrink:0;">
                <?= strtoupper(substr($client['client_name'], 0, 1)) ?>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold d-flex align-items-center gap-2 flex-wrap">
                    <?= htmlspecialchars($client['client_name']) ?>
                    <?php if (!empty($client['company_name'])): ?>
                    <span class="text-muted fw-normal small">· <?= htmlspecialchars($client['company_name']) ?></span>
                    <?php endif; ?>
                    <span class="lead-badge"><?= $client['lead_status'] ?></span>
                </div>
                <?php if (!empty($client['client_phone'])): ?>
                <small class="text-muted"><i class="fas fa-phone fa-xs me-1"></i><?= htmlspecialchars($client['client_phone']) ?></small>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right side: mini invoice summary -->
        <div class="d-flex align-items-center gap-3 flex-shrink-0 flex-wrap">
            <span class="text-success fw-semibold small"><?= formatMoney($client['paid_amount']) ?> <span class="text-muted fw-normal">paid</span></span>
            <span class="text-danger small"><?= formatMoney($client['unpaid_amount']) ?> <span class="text-muted fw-normal">unpaid</span></span>
            <span class="fw-bold small"><?= formatMoney($client['total_invoiced']) ?></span>
            <span class="badge bg-light text-dark"><?= $client['invoice_count'] ?> inv</span>
            <i class="fas fa-chevron-down collapse-icon text-muted"></i>
        </div>
    </div>

    <!-- Invoices collapse -->
    <div class="collapse show" id="<?= $collapseId ?>">
        <?php if (empty($client['invoices'])): ?>
        <div class="text-center text-muted py-3 small">No invoices for this client.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table inv-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4 py-2">Invoice #</th>
                        <th class="py-2 text-end">Total (<?= $currency_symbol ?>)</th>
                        <th class="py-2 text-end">Received (<?= $currency_symbol ?>)</th>
                        <th class="py-2 text-end">Balance (<?= $currency_symbol ?>)</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2">Due Date</th>
                        <th class="py-2 pe-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($client['invoices'] as $inv):
                    $balance = $inv['grand_total'] - $inv['amount_received'];
                ?>
                <tr>
                    <td class="ps-4 fw-semibold text-primary"><?= htmlspecialchars($inv['invoice_no']) ?></td>
                    <td class="text-end"><?= formatMoney($inv['grand_total']) ?></td>
                    <td class="text-end text-success"><?= formatMoney($inv['amount_received']) ?></td>
                    <td class="text-end <?= $balance > 0 ? 'text-danger' : 'text-success' ?>"><?= formatMoney($balance) ?></td>
                    <td class="text-center">
                        <?php if ($inv['status'] === 'Paid'): ?>
                            <span class="status-paid">Paid</span>
                        <?php elseif ($inv['status'] === 'Unpaid'): ?>
                            <span class="status-unpaid">Unpaid</span>
                        <?php else: ?>
                            <span class="status-partial">Partial</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= !empty($inv['due_date']) ? date('d M Y', strtotime($inv['due_date'])) : '—' ?></td>
                    <td class="text-center pe-4">
                        <a href="<?= BASE_URL ?>invoices/show/<?= $inv['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Invoice">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold small">
                        <td class="ps-4 py-2">Client Total</td>
                        <td class="text-end"><?= formatMoney($client['total_invoiced']) ?></td>
                        <td class="text-end text-success"><?= formatMoney($client['paid_amount']) ?></td>
                        <td class="text-end text-danger"><?= formatMoney($client['unpaid_amount'] + $client['partial_amount']) ?></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endforeach; ?>

<!-- ===== GRAND TOTAL SUMMARY ===== -->
<div class="card border-0 shadow-sm mt-2" style="border-radius:12px; overflow:hidden;">
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4 py-3">Grand Total — <?= htmlspecialchars($salesman['name']) ?></th>
                    <th class="py-3 text-end">Total Invoiced</th>
                    <th class="py-3 text-end">Paid</th>
                    <th class="py-3 text-end">Unpaid</th>
                    <th class="py-3 text-end pe-4">Partial</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold fs-6">
                    <td class="ps-4 py-3"><?= count($clients) ?> clients · <?= $totals['total_invoices'] ?> invoices</td>
                    <td class="text-end"><?= formatMoney($totals['total_invoiced']) ?></td>
                    <td class="text-end text-success"><?= formatMoney($totals['paid_amount']) ?></td>
                    <td class="text-end text-danger"><?= formatMoney($totals['unpaid_amount']) ?></td>
                    <td class="text-end text-warning pe-4"><?= formatMoney($totals['partial_amount']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

<!-- Bootstrap JS for collapse (already loaded via header) -->
<script>
// Rotate chevron icon on collapse
document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(el) {
    var target = document.querySelector(el.getAttribute('data-bs-target'));
    if (!target) return;
    target.addEventListener('show.bs.collapse', function() {
        el.querySelector('.collapse-icon').style.transform = 'rotate(0deg)';
    });
    target.addEventListener('hide.bs.collapse', function() {
        el.querySelector('.collapse-icon').style.transform = 'rotate(-90deg)';
    });
});
</script>
