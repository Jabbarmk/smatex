<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .vs-wrapper { box-shadow: none !important; border: none !important; }
    a[href]:after { content: none !important; }
}
@media screen {
    .vs-wrapper { max-width: 900px; margin: 0 auto; }
}
.vs-accent      { color: #e8602c; }
.vs-accent-line { border-top: 3px solid #e8602c; }
.vs-table th    { font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; color: #6c757d; background: #f8f9fa; }
.vs-table td    { font-size: .875rem; vertical-align: middle; }
.summary-box    { border-radius: 10px; padding: 1rem 1.25rem; }
.mode-cash      { background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
.mode-cheque    { background:#dbeafe; color:#1e40af; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
.mode-transfer  { background:#fef9c3; color:#713f12; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
.mode-other     { background:#f3f4f6; color:#374151; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
.vs-footer      { border-top: 1px solid #ddd; padding-top: .75rem; text-align: center; color: #aaa; font-size: .75rem; }
</style>

<!-- Action bar -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print flex-wrap gap-2">
    <a href="<?= BASE_URL ?>statements" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Back to Statements
    </a>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i>Print / Save PDF
        </button>
        <button onclick="downloadPDF()" class="btn btn-primary btn-sm">
            <i class="fas fa-download me-1"></i>Download PDF
        </button>
    </div>
</div>

<?php
$companyName    = $settings['company_name']    ?? '';
$companyAddress = $settings['company_address'] ?? '';
$companyEmail   = $settings['company_email']   ?? '';
$companyPhone   = $settings['company_phone']   ?? '';
$companyTrn     = $settings['company_trn']     ?? '';
$payeeName      = $client['company_name'] ?: $client['lead_name'];

// Group totals by payment mode
$byMode = [];
foreach ($vouchers as $v) {
    $m = $v['payment_mode'];
    $byMode[$m] = ($byMode[$m] ?? 0) + $v['amount'];
}
arsort($byMode);
?>

<!-- Statement Document -->
<div class="vs-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="vs-content">

    <!-- HEADER -->
    <div class="row align-items-center mb-3">
        <div class="col-md-4 d-flex align-items-center gap-3">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height:65px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($companyName ?: 'Company') ?></h4>
                <?php if ($companyTrn): ?>
                    <small class="vs-accent fw-bold">TRN: <?= htmlspecialchars($companyTrn) ?></small><br>
                <?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:70px;width:auto;display:block;margin:0 auto;">
        </div>
        <div class="col-md-4 text-end">
            <h3 class="fw-bold text-uppercase mb-1" style="color:#3a3f51;">Payment Voucher<br>Statement</h3>
            <small class="text-muted">Generated: <?= date('d M Y') ?></small>
        </div>
    </div>

    <div class="vs-accent-line mb-4"></div>

    <!-- CLIENT + SUMMARY -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Client</p>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($payeeName) ?></h5>
                <?php if ($client['company_name'] && $client['lead_name'] !== $client['company_name']): ?>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($client['lead_name']) ?></p>
                <?php endif; ?>
                <?php if (!empty($client['phone'])): ?>
                    <p class="mb-0 text-muted small"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($client['phone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($client['email'])): ?>
                    <p class="mb-0 text-muted small"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($client['email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($client['emirates'])): ?>
                    <p class="mb-0 text-muted small"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($client['emirates']) ?></p>
                <?php endif; ?>
                <?php if (!empty($client['salesman_name'])): ?>
                    <p class="mb-0 text-muted small mt-1"><i class="fas fa-user-tie me-1"></i>Sales: <?= htmlspecialchars($client['salesman_name']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row g-2 h-100 align-content-start">
                <div class="col-6">
                    <div class="summary-box text-center h-100 d-flex flex-column justify-content-center" style="background:#fff7ed; border:1px solid #fed7aa;">
                        <div class="fw-bold fs-5 vs-accent"><?= formatMoney($total_amount) ?></div>
                        <small class="text-muted">Total Paid</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="summary-box text-center h-100 d-flex flex-column justify-content-center" style="background:#f8f9fa; border:1px solid #e9ecef;">
                        <div class="fw-bold fs-5" style="color:#3a3f51;"><?= count($vouchers) ?></div>
                        <small class="text-muted">Vouchers</small>
                    </div>
                </div>
                <?php foreach ($byMode as $mode => $mAmt): ?>
                <div class="col-6">
                    <div class="summary-box" style="background:#f8f9fa; border:1px solid #e9ecef; font-size:.8rem;">
                        <div class="fw-semibold"><?= htmlspecialchars($mode) ?></div>
                        <div class="text-muted"><?= formatMoney($mAmt) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- VOUCHERS TABLE -->
    <div class="table-responsive mb-4">
        <table class="table vs-table mb-0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="py-3 ps-3" style="width:40px;">#</th>
                    <th class="py-3">Voucher No</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Payment Mode</th>
                    <th class="py-3">Reference</th>
                    <th class="py-3">Invoices Covered</th>
                    <th class="py-3 text-end pe-3">Amount (<?= htmlspecialchars($currency_symbol) ?>)</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($vouchers)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-file-invoice fa-2x mb-2 d-block opacity-25"></i>
                        No payment vouchers found for this client.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($vouchers as $i => $v): ?>
                <?php
                    $modeClass = match($v['payment_mode']) {
                        'Cash'          => 'mode-cash',
                        'Cheque'        => 'mode-cheque',
                        'Bank Transfer' => 'mode-transfer',
                        default         => 'mode-other',
                    };
                ?>
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>paymentvoucher/show/<?= $v['id'] ?>" class="fw-semibold text-primary text-decoration-none no-print">
                            <?= htmlspecialchars($v['voucher_no']) ?>
                        </a>
                        <span class="fw-semibold" style="display:none;" class="print-only"><?= htmlspecialchars($v['voucher_no']) ?></span>
                    </td>
                    <td class="text-muted"><?= date('d M Y', strtotime($v['payment_date'])) ?></td>
                    <td><span class="<?= $modeClass ?>"><?= htmlspecialchars($v['payment_mode']) ?></span></td>
                    <td class="text-muted small"><?= htmlspecialchars($v['reference_number'] ?: 'â€”') ?></td>
                    <td class="text-muted small"><?= htmlspecialchars($v['invoices_covered'] ?: 'â€”') ?></td>
                    <td class="text-end pe-3 fw-semibold"><?= formatMoney($v['amount']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid #e5e7eb; background:#fff8f5;">
                    <td colspan="6" class="ps-3 py-3 fw-bold vs-accent">Total (<?= count($vouchers) ?> vouchers)</td>
                    <td class="text-end pe-3 fw-bold vs-accent fs-5"><?= formatMoney($total_amount) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer note -->
    <div class="row pt-2" style="border-top:1px solid #e5e7eb;">
        <div class="col-md-7">
            <p class="text-muted small mb-0">This is a system-generated payment voucher statement.</p>
            <p class="text-muted small mb-0">Generated on <?= date('d F Y, H:i') ?></p>
        </div>
        <div class="col-md-5 text-end">
            <p class="text-muted small mb-1">Total Amount Paid</p>
            <h4 class="fw-bold vs-accent mb-0"><?= htmlspecialchars($currency_symbol) ?> <?= formatMoney($total_amount) ?></h4>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="vs-footer mt-4">
        <?= htmlspecialchars($companyName) ?>
        <?php if ($companyAddress): ?> | <?= htmlspecialchars(str_replace("\n", ", ", $companyAddress)) ?><?php endif; ?>
        <br>
        <?php if ($companyTrn): ?>TRN: <?= htmlspecialchars($companyTrn) ?> | <?php endif; ?>
        <?= htmlspecialchars($companyEmail) ?>
        <?php if ($companyPhone): ?> | <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
    </div>

</div>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-danger px-4">
        <i class="fas fa-file-pdf me-2"></i>Save as PDF / Print
    </button>
    <button onclick="downloadPDF()" class="btn btn-primary px-4 ms-2">
        <i class="fas fa-download me-1"></i>Download PDF
    </button>
    <a href="<?= BASE_URL ?>statements" class="btn btn-light px-4 ms-2">
        <i class="fas fa-arrow-left me-1"></i>New Statement
    </a>
</div>

<script>
window.jsPDF = window.jspdf.jsPDF;
function downloadPDF() {
    const el   = document.getElementById('vs-content');
    const btns = document.querySelectorAll('.no-print');
    btns.forEach(b => b.style.display = 'none');
    html2canvas(el, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.85);
        const pdf     = new jsPDF('p', 'mm', 'a4');
        const w       = pdf.internal.pageSize.getWidth();
        const h       = (canvas.height * w) / canvas.width;
        const ph      = pdf.internal.pageSize.getHeight();
        if (h <= ph) {
            pdf.addImage(imgData, 'JPEG', 0, 0, w, h);
        } else {
            let pos = 0, rem = h;
            while (rem > 0) {
                pdf.addImage(imgData, 'JPEG', 0, pos, w, h);
                rem -= ph; pos -= ph;
                if (rem > 0) pdf.addPage();
            }
        }
        pdf.save('VoucherStatement_<?= preg_replace('/[^A-Za-z0-9_-]/', '_', $payeeName) ?>.pdf');
        btns.forEach(b => b.style.display = '');
    });
}
</script>

