<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .sv-wrapper { box-shadow: none !important; border: none !important; }
}
@media screen {
    .sv-wrapper { max-width: 860px; margin: 0 auto; }
}
.sv-wrapper { font-family: 'Inter', sans-serif; }
.sv-accent { color: #e8602c; }
.sv-accent-line { border-top: 3px solid #e8602c; }
.sv-table td, .sv-table th { padding: .6rem 1rem; font-size: .875rem; }
.sv-table thead { background: #3a3f51; color: #fff; font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; }
.sv-sig-line { border-top: 1px solid #555; width: 180px; margin-top: 50px; padding-top: 4px; text-align: center; font-size: .8rem; color: #555; }
.sv-footer { border-top: 1px solid #ddd; padding-top: .75rem; text-align: center; color: #aaa; font-size: .75rem; }
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print">
    <button onclick="window.print()" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Print / Save PDF</button>
    <button onclick="downloadPDF()" class="btn btn-primary"><i class="fas fa-download me-1"></i> Download PDF</button>
    <a href="<?= BASE_URL ?>salary/create" class="btn btn-success"><i class="fas fa-plus me-1"></i> New Voucher</a>
    <a href="<?= BASE_URL ?>salary" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<?php
$companyName    = $settings['company_name'] ?? 'Company';
$companyAddress = $settings['company_address'] ?? '';
$companyEmail   = $settings['company_email'] ?? '';
$companyPhone   = $settings['company_phone'] ?? '';
$companyWebsite = $settings['company_website'] ?? '';
$companyTrn     = $settings['company_trn'] ?? '';
?>

<!-- Salary Voucher Document -->
<div class="sv-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="voucher-content">

    <!-- ===== HEADER ===== -->
    <div class="row align-items-center mb-3">
        <div class="col-md-7 d-flex align-items-center gap-3">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height: 65px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($companyName) ?></h4>
                <?php if ($companyTrn): ?><small class="sv-accent fw-bold">TRN: <?= htmlspecialchars($companyTrn) ?></small><br><?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small>
            </div>
        </div>
        <div class="col-md-5 text-end">
            <h2 class="fw-bold text-uppercase mb-1" style="color:#3a3f51;">SALARY VOUCHER</h2>
            <h5 class="fw-bold sv-accent mb-0"><?= htmlspecialchars($payment['voucher_no']) ?></h5>
            <small class="text-muted">Date: <?= date('d M Y', strtotime($payment['payment_date'])) ?></small>
        </div>
    </div>

    <div class="sv-accent-line mb-4"></div>

    <!-- ===== EMPLOYEE + VOUCHER DETAILS ===== -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Employee Details</p>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($payment['full_name']) ?></h5>
                <p class="mb-0 text-muted"><?= htmlspecialchars($payment['designation']) ?><?= $payment['department'] ? ' · ' . htmlspecialchars($payment['department']) : '' ?></p>
                <p class="mb-0 text-muted small">Emp No: <strong><?= htmlspecialchars($payment['employee_no']) ?></strong></p>
                <?php if ($payment['nationality']): ?><p class="mb-0 text-muted small">Nationality: <?= htmlspecialchars($payment['nationality']) ?></p><?php endif; ?>
                <?php if ($payment['passport_no']): ?><p class="mb-0 text-muted small">Passport: <?= htmlspecialchars($payment['passport_no']) ?></p><?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Payment Details</p>
                <table class="table table-sm table-borderless mb-0" style="font-size:.875rem;">
                    <tr><td class="text-muted ps-0 fw-semibold">Voucher No:</td><td class="text-end pe-0 fw-bold"><?= htmlspecialchars($payment['voucher_no']) ?></td></tr>
                    <tr><td class="text-muted ps-0 fw-semibold">Salary Period:</td><td class="text-end pe-0"><?= htmlspecialchars($payment['payment_month']) ?> <?= $payment['payment_year'] ?></td></tr>
                    <tr><td class="text-muted ps-0 fw-semibold">Payment Date:</td><td class="text-end pe-0"><?= date('d M Y', strtotime($payment['payment_date'])) ?></td></tr>
                    <tr><td class="text-muted ps-0 fw-semibold">Payment Mode:</td><td class="text-end pe-0"><?= htmlspecialchars($payment['payment_mode']) ?></td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== EARNINGS TABLE ===== -->
    <div class="table-responsive mb-4">
        <table class="table sv-table mb-0" style="border:1px solid #eee;">
            <thead>
                <tr>
                    <th colspan="2">Earnings</th>
                    <th class="text-end">Amount (<?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?>)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:40px;" class="text-muted">1</td>
                    <td>Basic Salary</td>
                    <td class="text-end"><?= formatMoney($payment['basic_salary']) ?></td>
                </tr>
                <?php if ($payment['housing_allowance'] > 0): ?>
                <tr>
                    <td class="text-muted">2</td>
                    <td>Housing Allowance</td>
                    <td class="text-end"><?= formatMoney($payment['housing_allowance']) ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($payment['transport_allowance'] > 0): ?>
                <tr>
                    <td class="text-muted">3</td>
                    <td>Transport Allowance</td>
                    <td class="text-end"><?= formatMoney($payment['transport_allowance']) ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($payment['other_allowance'] > 0): ?>
                <tr>
                    <td class="text-muted">4</td>
                    <td>Other Allowance</td>
                    <td class="text-end"><?= formatMoney($payment['other_allowance']) ?></td>
                </tr>
                <?php endif; ?>
                <!-- Gross -->
                <tr style="border-top:2px solid #ddd; background:#f8f9fa;">
                    <td colspan="2" class="fw-bold">Gross Salary</td>
                    <td class="text-end fw-bold"><?= formatMoney($payment['gross_salary']) ?></td>
                </tr>
                <?php if ($payment['deductions'] > 0): ?>
                <tr class="text-danger">
                    <td class="text-muted"></td>
                    <td>Deductions<?= $payment['deduction_reason'] ? ' — <span class="text-muted fw-normal">' . htmlspecialchars($payment['deduction_reason']) . '</span>' : '' ?></td>
                    <td class="text-end">-<?= formatMoney($payment['deductions']) ?></td>
                </tr>
                <?php endif; ?>
                <!-- Net -->
                <tr style="border-top:2px solid #3a3f51; background:#fff8f5;">
                    <td colspan="2" class="fw-bold sv-accent fs-5">Net Salary Payable</td>
                    <td class="text-end fw-bold sv-accent fs-5"><?= formatMoney($payment['net_salary']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ===== BANK DETAILS ===== -->
    <?php if (!empty($payment['bank_name']) || !empty($payment['iban'])): ?>
    <div class="p-3 mb-4 rounded" style="background:#f8f9fa; border-left:4px solid #3a3f51;">
        <h6 class="fw-bold mb-2 small text-uppercase">Employee Bank Details</h6>
        <div class="row" style="font-size:.875rem;">
            <?php if ($payment['bank_name']): ?><div class="col-md-4"><span class="text-muted">Bank:</span> <?= htmlspecialchars($payment['bank_name']) ?></div><?php endif; ?>
            <?php if ($payment['bank_account']): ?><div class="col-md-4"><span class="text-muted">Account:</span> <?= htmlspecialchars($payment['bank_account']) ?></div><?php endif; ?>
            <?php if ($payment['iban']): ?><div class="col-md-4"><span class="text-muted">IBAN:</span> <?= htmlspecialchars($payment['iban']) ?></div><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($payment['notes'])): ?>
    <p class="text-muted small mb-4"><strong>Notes:</strong> <?= htmlspecialchars($payment['notes']) ?></p>
    <?php endif; ?>

    <!-- ===== SIGNATURES ===== -->
    <div class="row mt-5 mb-4">
        <div class="col-4 text-center">
            <div class="sv-sig-line d-inline-block">
                Prepared By
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="sv-sig-line d-inline-block">
                Authorized By<br>
                <small class="text-muted"><?= htmlspecialchars($companyName) ?></small>
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="sv-sig-line d-inline-block">
                Received By<br>
                <small class="text-muted"><?= htmlspecialchars($payment['full_name']) ?></small>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="sv-footer mt-4">
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
    const el = document.getElementById('voucher-content');
    const btns = document.querySelectorAll('.no-print');
    btns.forEach(b => b.style.display = 'none');
    html2canvas(el, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.85);
        const pdf = new jsPDF('p', 'mm', 'a4');
        const w = pdf.internal.pageSize.getWidth();
        const h = (canvas.height * w) / canvas.width;
        const ph = pdf.internal.pageSize.getHeight();
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
        pdf.save('SalaryVoucher_<?= $payment['voucher_no'] ?>.pdf');
        btns.forEach(b => b.style.display = '');
    });
}
</script>
