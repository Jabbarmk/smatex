<!-- Print Styles -->
<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding:0 !important; margin:0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .stmt-wrapper { box-shadow: none !important; border: none !important; }
    a[href]:after { content: none !important; }
    .badge { border: 1px solid #ccc !important; }
}
@media screen {
    .stmt-wrapper { max-width: 860px; margin: 0 auto; }
}
.stmt-header-line { border-top: 3px solid #6366f1; padding-top: 1.5rem; }
.stmt-badge-paid    { background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.stmt-badge-unpaid  { background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.stmt-badge-partial { background:#fef9c3; color:#713f12; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.stmt-table th { font-size:.75rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d; background:#f8f9fa; }
.stmt-table td { font-size:.875rem; vertical-align:middle; }
.summary-box { border-radius:10px; padding:1rem 1.25rem; }
.section-title { font-size:.8rem; text-transform:uppercase; letter-spacing:.8px; color:#6366f1; font-weight:700; }
</style>

<!-- Top action bar (screen only) -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print flex-wrap gap-2">
    <a href="<?= BASE_URL ?>statements" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Back to Statements
    </a>
    <button onclick="window.print()" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf me-2"></i>Export / Print PDF
    </button>
</div>

<!-- Statement Document -->
<div class="stmt-wrapper bg-white p-4 p-md-5 rounded shadow-sm">

    <!-- Company + Statement Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h3 class="fw-bold mb-0" style="color:#6366f1;"><?= htmlspecialchars($company_name ?: 'Company') ?></h3>
            <p class="text-muted small mb-0">Project Statement of Account</p>
        </div>
        <div class="col-auto text-center">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:70px;width:auto;display:block;margin:0 auto;">
        </div>
        <div class="col-auto text-end">
            <p class="text-muted small mb-0">Date Generated:</p>
            <strong><?= date('d F Y') ?></strong>
        </div>
    </div>

    <div class="stmt-header-line mb-4">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1 text-muted small fw-semibold text-uppercase">Project (Approved Quotation)</p>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($project['quotation_no']) ?></h5>
                <?php if (!empty($project['company_name'])): ?>
                <p class="mb-0 fw-semibold"><?= htmlspecialchars($project['company_name']) ?></p>
                <p class="text-muted small mb-0"><?= htmlspecialchars($project['lead_name']) ?></p>
                <?php else: ?>
                <p class="mb-0 fw-semibold"><?= htmlspecialchars($project['lead_name']) ?></p>
                <?php endif; ?>
                <div class="text-muted small mt-1">
                    <?php if (!empty($project['phone'])): ?><i class="fas fa-phone me-1"></i><?= htmlspecialchars($project['phone']) ?><?php endif; ?>
                    <?php if (!empty($project['email'])): ?> &bull; <i class="fas fa-envelope me-1 ms-1"></i><?= htmlspecialchars($project['email']) ?><?php endif; ?>
                </div>
                <?php if (!empty($project['emirates'])): ?>
                <div class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($project['emirates']) ?></div>
                <?php endif; ?>
                <?php if (!empty($project['salesman_name'])): ?>
                <div class="text-muted small mt-1"><i class="fas fa-user-tie me-1"></i>Sales: <?= htmlspecialchars($project['salesman_name']) ?></div>
                <?php endif; ?>
                <div class="text-muted small mt-1">
                    <i class="fas fa-check-circle me-1 text-success"></i>Approved
                    &bull; Quotation Date: <?= date('d M Y', strtotime($project['created_at'])) ?>
                </div>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="row g-2">
                    <div class="col-4">
                        <div class="summary-box text-center" style="background:#eef2ff;">
                            <div class="fw-bold" style="color:#6366f1;"><?= formatMoney($project_total) ?></div>
                            <small class="text-muted">Project Total</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="summary-box text-center" style="background:#f0fdf4;">
                            <div class="fw-bold text-success"><?= formatMoney($total_received) ?></div>
                            <small class="text-muted">Received</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="summary-box text-center" style="background:#fef2f2;">
                            <div class="fw-bold text-danger"><?= formatMoney($total_balance_due) ?></div>
                            <small class="text-muted">Balance Due</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices raised against this project -->
    <p class="section-title mb-2"><i class="fas fa-file-invoice me-1"></i>Invoices</p>
    <div class="table-responsive mb-4">
        <table class="table stmt-table mb-0 align-middle" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="py-3 ps-3" style="width:45px;">#</th>
                    <th class="py-3">Invoice Number</th>
                    <th class="py-3">Invoice Date</th>
                    <th class="py-3">Due Date</th>
                    <th class="py-3 text-end">Total (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end">Paid (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end">Balance (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($invoices)): ?>
                <tr><td colspan="8" class="text-center text-muted py-5">No invoices have been raised against this project yet.</td></tr>
            <?php else: ?>
            <?php foreach ($invoices as $i => $inv): ?>
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                    <td class="text-primary fw-semibold"><?= htmlspecialchars($inv['invoice_no']) ?></td>
                    <td class="text-muted small"><?= date('d M Y', strtotime($inv['created_at'])) ?></td>
                    <td class="text-muted small"><?= !empty($inv['due_date']) ? date('d M Y', strtotime($inv['due_date'])) : '&mdash;' ?></td>
                    <td class="text-end fw-semibold"><?= formatMoney($inv['grand_total']) ?></td>
                    <td class="text-end text-success"><?= formatMoney($inv['amount_received']) ?></td>
                    <td class="text-end <?= $inv['balance_due'] > 0 ? 'text-danger' : 'text-success' ?>"><?= formatMoney($inv['balance_due']) ?></td>
                    <td class="text-center">
                        <?php if ($inv['invoice_status'] === 'Paid'): ?>
                            <span class="stmt-badge-paid">Paid</span>
                        <?php elseif ($inv['invoice_status'] === 'Partial'): ?>
                            <span class="stmt-badge-partial">Partial</span>
                        <?php else: ?>
                            <span class="stmt-badge-unpaid">Unpaid</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid #e5e7eb; background:#f8f9fa;">
                    <td colspan="4" class="ps-3 py-3 fw-bold">Total (<?= count($invoices) ?> invoices)</td>
                    <td class="text-end fw-bold"><?= formatMoney($total_invoiced) ?></td>
                    <td class="text-end fw-bold text-success"><?= formatMoney($total_received) ?></td>
                    <td class="text-end fw-bold text-danger"><?= formatMoney($invoice_balance) ?></td>
                    <td class="text-center">
                        <small class="text-success fw-bold"><?= $paid_count ?> paid</small><br>
                        <small class="text-danger"><?= $unpaid_count ?> unpaid</small>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Payment Statement -->
    <p class="section-title mb-2"><i class="fas fa-money-bill-wave me-1"></i>Payments Received</p>
    <div class="table-responsive">
        <table class="table stmt-table mb-0 align-middle" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="py-3 ps-3" style="width:45px;">#</th>
                    <th class="py-3">Receipt No</th>
                    <th class="py-3">Payment Date</th>
                    <th class="py-3">Against Invoice</th>
                    <th class="py-3">Mode</th>
                    <th class="py-3">Reference</th>
                    <th class="py-3 text-end">Amount (<?= $currency_symbol ?>)</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($payments)): ?>
                <tr><td colspan="7" class="text-center text-muted py-5">No payments received for this project yet.</td></tr>
            <?php else: ?>
            <?php foreach ($payments as $p => $pay): ?>
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td class="ps-3 text-muted"><?= $p + 1 ?></td>
                    <td class="fw-semibold" style="color:#6366f1;"><?= htmlspecialchars($pay['receipt_no']) ?></td>
                    <td class="text-muted small"><?= date('d M Y', strtotime($pay['payment_date'])) ?></td>
                    <td class="text-primary fw-semibold"><?= htmlspecialchars($pay['invoice_no']) ?></td>
                    <td class="text-muted small"><?= htmlspecialchars($pay['payment_mode'] ?: '&mdash;') ?></td>
                    <td class="text-muted small"><?= !empty($pay['reference_number']) ? htmlspecialchars($pay['reference_number']) : '&mdash;' ?></td>
                    <td class="text-end fw-semibold text-success"><?= formatMoney($pay['amount_paid']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid #e5e7eb; background:#f8f9fa;">
                    <td colspan="6" class="ps-3 py-3 fw-bold">Total Received (<?= count($payments) ?> payments)</td>
                    <td class="text-end fw-bold text-success"><?= formatMoney($total_received) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Balance Statement -->
    <div class="row mt-4 pt-3" style="border-top:1px solid #e5e7eb;">
        <div class="col-md-6">
            <p class="text-muted small mb-0">This is a system-generated project statement.</p>
            <p class="text-muted small mb-0">Generated on <?= date('d F Y, H:i') ?></p>
        </div>
        <div class="col-md-6">
            <table class="table table-sm mb-0" style="font-size:.85rem;">
                <tr><td class="text-muted ps-0">Project Total (Approved Quotation)</td><td class="text-end fw-semibold"><?= formatMoney($project_total) ?></td></tr>
                <tr><td class="text-muted ps-0">Total Invoiced</td><td class="text-end fw-semibold"><?= formatMoney($total_invoiced) ?></td></tr>
                <tr><td class="text-muted ps-0">Amount Received</td><td class="text-end text-success fw-semibold">(<?= formatMoney($total_received) ?>)</td></tr>
                <tr><td class="text-muted ps-0">Balance on Invoices</td>
                    <td class="text-end fw-semibold <?= $invoice_balance > 0 ? 'text-danger' : 'text-success' ?>"><?= formatMoney($invoice_balance) ?></td></tr>
                <tr style="border-top:2px solid #e5e7eb;">
                    <td class="ps-0 fw-bold">Total Balance Due</td>
                    <td class="text-end fw-bold <?= $total_balance_due > 0 ? 'text-danger' : 'text-success' ?>"><?= formatMoney($total_balance_due) ?></td>
                </tr>
            </table>
        </div>
    </div>

</div>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-danger px-4">
        <i class="fas fa-file-pdf me-2"></i>Save as PDF / Print
    </button>
    <a href="<?= BASE_URL ?>statements" class="btn btn-light px-4 ms-2">
        <i class="fas fa-arrow-left me-1"></i>New Statement
    </a>
</div>
