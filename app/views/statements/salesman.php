<!-- Print Styles -->
<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding:0 !important; margin:0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .stmt-wrapper { box-shadow: none !important; border: none !important; }
    .page-break { page-break-before: always; }
    a[href]:after { content: none !important; }
    .badge { border: 1px solid #ccc !important; }
}
@media screen {
    .stmt-wrapper { max-width: 900px; margin: 0 auto; }
}
.stmt-header-line { border-top: 3px solid #6366f1; padding-top: 1.5rem; }
.stmt-badge-paid    { background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.stmt-badge-unpaid  { background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.stmt-badge-partial { background:#fef9c3; color:#713f12; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.stmt-table th { font-size:.75rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d; background:#f8f9fa; }
.stmt-table td { font-size:.875rem; vertical-align:middle; }
.summary-box { border-radius:10px; padding:1rem 1.25rem; }
</style>

<!-- Top action bar (screen only) -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print flex-wrap gap-2">
    <a href="<?= BASE_URL ?>statements" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Back to Statements
    </a>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-2"></i>Export / Print PDF
        </button>
    </div>
</div>

<!-- Statement Document -->
<div class="stmt-wrapper bg-white p-4 p-md-5 rounded shadow-sm">

    <!-- Company + Statement Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h3 class="fw-bold mb-0" style="color:#6366f1;"><?= htmlspecialchars($company_name ?: 'Company') ?></h3>
            <p class="text-muted small mb-0">Sales Statement</p>
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
                <p class="mb-1 text-muted small fw-semibold text-uppercase">Salesman</p>
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($salesman['name']) ?></h5>
                <p class="text-muted small mb-0"><?= $salesman['role'] ?>
                    <?php if (!empty($salesman['email'])): ?> &bull; <?= htmlspecialchars($salesman['email']) ?><?php endif; ?>
                    <?php if (!empty($salesman['phone'])): ?> &bull; <?= htmlspecialchars($salesman['phone']) ?><?php endif; ?>
                </p>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <!-- Summary Boxes -->
                <div class="row g-2">
                    <div class="col-4">
                        <div class="summary-box text-center" style="background:#f0fdf4;">
                            <div class="fw-bold text-success"><?= formatMoney($total_received) ?></div>
                            <small class="text-muted">Received</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="summary-box text-center" style="background:#fef2f2;">
                            <div class="fw-bold text-danger"><?= formatMoney($total_balance) ?></div>
                            <small class="text-muted">Balance</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="summary-box text-center" style="background:#f5f3ff;">
                            <div class="fw-bold" style="color:#6366f1;"><?= formatMoney($total_amount) ?></div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Table -->
    <div class="table-responsive">
        <table class="table stmt-table mb-0 align-middle" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="py-3 ps-3" style="width:45px;">SL#</th>
                    <th class="py-3">Lead / Client</th>
                    <th class="py-3">Company</th>
                    <th class="py-3">Invoice #</th>
                    <th class="py-3">Date</th>
                    <th class="py-3 text-end">Amount (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end">Received (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-end">Balance (<?= $currency_symbol ?>)</th>
                    <th class="py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($invoices)): ?>
                <tr><td colspan="9" class="text-center text-muted py-5">No invoices found for this salesman.</td></tr>
            <?php else: ?>
            <?php foreach ($invoices as $i => $inv):
                $balance = $inv['grand_total'] - $inv['amount_received'];
            ?>
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($inv['lead_name']) ?></td>
                    <td class="text-muted small"><?= htmlspecialchars($inv['company_name']) ?></td>
                    <td class="text-primary fw-semibold"><?= htmlspecialchars($inv['invoice_no']) ?></td>
                    <td class="text-muted small"><?= date('d M Y', strtotime($inv['created_at'])) ?></td>
                    <td class="text-end"><?= formatMoney($inv['grand_total']) ?></td>
                    <td class="text-end text-success"><?= formatMoney($inv['amount_received']) ?></td>
                    <td class="text-end <?= $balance > 0 ? 'text-danger' : 'text-success' ?>"><?= formatMoney($balance) ?></td>
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
                    <td colspan="5" class="ps-3 py-3 fw-bold">Total (<?= count($invoices) ?> invoices)</td>
                    <td class="text-end fw-bold"><?= formatMoney($total_amount) ?></td>
                    <td class="text-end fw-bold text-success"><?= formatMoney($total_received) ?></td>
                    <td class="text-end fw-bold text-danger"><?= formatMoney($total_balance) ?></td>
                    <td class="text-center">
                        <small class="text-success fw-bold"><?= $paid_count ?> paid</small><br>
                        <small class="text-danger"><?= $unpaid_count ?> unpaid</small>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer note -->
    <div class="mt-4 pt-3" style="border-top:1px solid #e5e7eb;">
        <div class="row">
            <div class="col-md-8">
                <p class="text-muted small mb-0">This is a system-generated statement and does not require a signature.</p>
                <p class="text-muted small mb-0">Generated on <?= date('d F Y, H:i') ?></p>
            </div>
            <div class="col-md-4 text-end">
                <p class="text-muted small mb-0">Outstanding Balance:</p>
                <h5 class="fw-bold text-danger mb-0"><?= formatMoney($total_balance) ?></h5>
            </div>
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

