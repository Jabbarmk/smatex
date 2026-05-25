<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .pv-wrapper { box-shadow: none !important; border: none !important; }
}
@media screen {
    .pv-wrapper { max-width: 860px; margin: 0 auto; }
}
.pv-wrapper { font-family: 'Inter', sans-serif; }
.pv-accent       { color: #e8602c; }
.pv-accent-line  { border-top: 3px solid #e8602c; }
.pv-table td, .pv-table th { padding: .6rem 1rem; font-size: .875rem; }
.pv-table thead  { background: #3a3f51; color: #fff; font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; }
.pv-sig-line     { border-top: 1px solid #555; width: 180px; margin-top: 50px; padding-top: 4px; text-align: center; font-size: .8rem; color: #555; }
.pv-footer       { border-top: 1px solid #ddd; padding-top: .75rem; text-align: center; color: #aaa; font-size: .75rem; }
.pv-badge-cash     { background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
.pv-badge-cheque   { background:#dbeafe; color:#1e40af; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
.pv-badge-transfer { background:#fef9c3; color:#713f12; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
</style>

<!-- Action bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print flex-wrap">
    <button onclick="window.print()" class="btn btn-outline-danger">
        <i class="fas fa-file-pdf me-1"></i> Print / Save PDF
    </button>
    <button onclick="downloadPDF()" class="btn btn-primary">
        <i class="fas fa-download me-1"></i> Download PDF
    </button>
    <a href="<?= BASE_URL ?>paymentvoucher/create" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> New Voucher
    </a>
    <a href="<?= BASE_URL ?>paymentvoucher" class="btn btn-light">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<?php
$companyName    = $settings['company_name']    ?? 'Company';
$companyAddress = $settings['company_address'] ?? '';
$companyEmail   = $settings['company_email']   ?? '';
$companyPhone   = $settings['company_phone']   ?? '';
$companyWebsite = $settings['company_website'] ?? '';
$companyTrn     = $settings['company_trn']     ?? '';
$currency       = $settings['currency_symbol'] ?? 'AED';

$payeeName = $voucher['company_name'] ?: ($voucher['lead_name'] ?? '');
$payeeContact = $voucher['lead_name'] ?? '';

$modeBadge = match($voucher['payment_mode']) {
    'Cash'          => 'pv-badge-cash',
    'Cheque'        => 'pv-badge-cheque',
    'Bank Transfer' => 'pv-badge-transfer',
    default         => 'pv-badge-cash',
};
?>

<!-- Voucher Document -->
<div class="pv-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="voucher-content">

    <!-- HEADER -->
    <div class="row align-items-center mb-3">
        <div class="col-md-4 d-flex align-items-center gap-3">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height:65px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($companyName) ?></h4>
                <?php if ($companyTrn): ?>
                    <small class="pv-accent fw-bold">TRN: <?= htmlspecialchars($companyTrn) ?></small><br>
                <?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:70px;width:auto;display:block;margin:0 auto;">
        </div>
        <div class="col-md-4 text-end">
            <h2 class="fw-bold text-uppercase mb-1" style="color:#3a3f51;">PAYMENT VOUCHER</h2>
            <h5 class="fw-bold pv-accent mb-0"><?= htmlspecialchars($voucher['voucher_no']) ?></h5>
            <small class="text-muted">Date: <?= date('d M Y', strtotime($voucher['payment_date'])) ?></small>
        </div>
    </div>

    <div class="pv-accent-line mb-4"></div>

    <!-- PAYEE + VOUCHER DETAILS -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Pay To</p>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($payeeName ?: 'â€”') ?></h5>
                <?php if ($payeeName && $payeeContact && $payeeContact !== $payeeName): ?>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($payeeContact) ?></p>
                <?php endif; ?>
                <?php if (!empty($voucher['client_phone'])): ?>
                    <p class="mb-0 text-muted small"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($voucher['client_phone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($voucher['client_email'])): ?>
                    <p class="mb-0 text-muted small"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($voucher['client_email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($voucher['emirates'])): ?>
                    <p class="mb-0 text-muted small"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($voucher['emirates']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Voucher Details</p>
                <table class="table table-sm table-borderless mb-0" style="font-size:.875rem;">
                    <tr>
                        <td class="text-muted ps-0 fw-semibold">Voucher No:</td>
                        <td class="text-end pe-0 fw-bold"><?= htmlspecialchars($voucher['voucher_no']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0 fw-semibold">Date:</td>
                        <td class="text-end pe-0"><?= date('d M Y', strtotime($voucher['payment_date'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0 fw-semibold">Payment Mode:</td>
                        <td class="text-end pe-0">
                            <span class="<?= $modeBadge ?>"><?= htmlspecialchars($voucher['payment_mode']) ?></span>
                        </td>
                    </tr>
                    <?php if (!empty($voucher['reference_number'])): ?>
                    <tr>
                        <td class="text-muted ps-0 fw-semibold">Reference:</td>
                        <td class="text-end pe-0"><?= htmlspecialchars($voucher['reference_number']) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- PAYMENT STATEMENTS TABLE -->
    <?php if (!empty($items)): ?>
    <div class="table-responsive mb-4">
        <table class="table pv-table mb-0" style="border:1px solid #eee;">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Invoice No</th>
                    <th>Description</th>
                    <th class="text-end">Amount (<?= htmlspecialchars($currency) ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td class="fw-semibold text-primary"><?= htmlspecialchars($item['invoice_no'] ?: 'â€”') ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td class="text-end"><?= formatMoney($item['amount']) ?></td>
                </tr>
                <?php endforeach; ?>
                <!-- Total row -->
                <tr style="border-top:2px solid #3a3f51; background:#fff8f5;">
                    <td colspan="3" class="fw-bold pv-accent fs-5">Total Amount Paid</td>
                    <td class="text-end fw-bold pv-accent fs-5"><?= formatMoney($voucher['amount']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <!-- Simple amount display when no line items -->
    <div class="p-4 mb-4 rounded text-center" style="background:#fff8f5; border:2px solid #e8602c;">
        <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Amount Paid</p>
        <h2 class="fw-bold pv-accent mb-0"><?= htmlspecialchars($currency) ?> <?= formatMoney($voucher['amount']) ?></h2>
        <?php if (!empty($voucher['description'])): ?>
        <p class="text-muted small mt-2 mb-0"><?= htmlspecialchars($voucher['description']) ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($voucher['description']) && !empty($items)): ?>
    <p class="text-muted small mb-4"><strong>Description:</strong> <?= htmlspecialchars($voucher['description']) ?></p>
    <?php endif; ?>

    <?php if (!empty($voucher['notes'])): ?>
    <p class="text-muted small mb-4"><strong>Notes:</strong> <?= htmlspecialchars($voucher['notes']) ?></p>
    <?php endif; ?>

    <!-- SIGNATURES -->
    <div class="row mt-5 mb-4">
        <div class="col-4 text-center">
            <div class="pv-sig-line d-inline-block">Prepared By</div>
        </div>
        <div class="col-4 text-center">
            <div class="pv-sig-line d-inline-block">
                Authorized By<br>
                <small class="text-muted"><?= htmlspecialchars($companyName) ?></small>
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="pv-sig-line d-inline-block">
                Received By<br>
                <small class="text-muted"><?= htmlspecialchars($payeeName) ?></small>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="pv-footer mt-4">
        <?= htmlspecialchars($companyName) ?>
        <?php if ($companyAddress): ?> | <?= htmlspecialchars(str_replace("\n", ", ", $companyAddress)) ?><?php endif; ?>
        <br>
        <?php if ($companyTrn): ?>TRN: <?= htmlspecialchars($companyTrn) ?> | <?php endif; ?>
        <?= htmlspecialchars($companyEmail) ?>
        <?php if ($companyPhone): ?> | <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
        <?php if ($companyWebsite): ?> | <?= htmlspecialchars($companyWebsite) ?><?php endif; ?>
    </div>

</div>

<script>
window.jsPDF = window.jspdf.jsPDF;
function downloadPDF() {
    const el   = document.getElementById('voucher-content');
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
        pdf.save('PaymentVoucher_<?= $voucher['voucher_no'] ?>.pdf');
        btns.forEach(b => b.style.display = '');
    });
}
</script>

