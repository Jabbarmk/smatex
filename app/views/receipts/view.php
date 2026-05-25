<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .rec-wrapper { box-shadow: none !important; border: none !important; }
    a[href]:after { content: none !important; }
}
@media screen {
    .rec-wrapper { max-width: 820px; margin: 0 auto; }
}
.rec-wrapper { font-family: 'Inter', sans-serif; }
.rec-accent { color: #e8602c; }
.rec-accent-bg { background: #3a3f51; color: #fff; }
.rec-accent-line { border-top: 3px solid #e8602c; }
.rec-sig-line { border-top: 1px solid #555; width: 200px; margin-top: 60px; padding-top: 5px; text-align: center; font-size: .85rem; color: #555; }
.rec-footer-bar { border-top: 1px solid #ddd; padding-top: .75rem; text-align: center; color: #999; font-size: .75rem; }
.badge-mode { font-size: .8rem; padding: .45em .75em; }
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print">
    <button onclick="window.print()" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Print / Save PDF</button>
    <button onclick="downloadPDF()" class="btn btn-primary"><i class="fas fa-download me-1"></i> Download PDF</button>
    <a href="<?= BASE_URL ?>receipts/edit/<?= $receipt['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</a>
    <a href="<?= BASE_URL ?>receipts" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<!-- Receipt Document -->
<div class="rec-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="receipt-content">

    <?php
    $companyName    = $settings['company_name']    ?? 'Company';
    $companyAddress = $settings['company_address'] ?? '';
    $companyEmail   = $settings['company_email']   ?? '';
    $companyPhone   = $settings['company_phone']   ?? '';
    $companyWebsite = $settings['company_website'] ?? '';
    $companyTrn     = $settings['company_trn']     ?? '';
    $bankDetails    = $settings['bank_details']    ?? '';
    ?>

    <!-- Header -->
    <div class="row align-items-center mb-3">
        <div class="col-md-4 d-flex align-items-center gap-3">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height: 65px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($companyName) ?></h4>
                <?php if ($companyTrn): ?>
                    <small class="rec-accent fw-bold">TRN: <?= htmlspecialchars($companyTrn) ?></small><br>
                <?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small><br>
                <small class="text-muted">
                    <?= htmlspecialchars($companyEmail) ?>
                    <?php if ($companyPhone): ?> | <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
                </small>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:70px;width:auto;display:block;margin:0 auto;">
        </div>
        <div class="col-md-4 text-end">
            <h1 class="fw-bold text-uppercase mb-1" style="font-size: 2.2rem; color: #3a3f51;">RECEIPT</h1>
            <h5 class="fw-bold rec-accent mb-0"><?= htmlspecialchars($receipt['receipt_no']) ?></h5>
            <small class="text-muted">Date: <?= date('M d, Y', strtotime($receipt['payment_date'])) ?></small>
        </div>
    </div>

    <div class="rec-accent-line mb-4"></div>

    <!-- Received From + Receipt Details -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Received From</p>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($lead['lead_name'] ?? $invoice['client_details']) ?></h5>
                <?php if (!empty($lead['company_name'])): ?>
                    <p class="mb-0 text-muted"><?= htmlspecialchars($lead['company_name']) ?></p>
                <?php endif; ?>
                <?php if (!empty($lead['email'])): ?>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($lead['email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($lead['phone'])): ?>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($lead['phone']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Receipt Details</p>
                <table class="table table-sm table-borderless mb-0" style="font-size:.875rem;">
                    <tr>
                        <td class="text-muted fw-semibold ps-0">Receipt #:</td>
                        <td class="text-end pe-0 fw-bold"><?= htmlspecialchars($receipt['receipt_no']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold ps-0">Invoice #:</td>
                        <td class="text-end pe-0">
                            <a href="<?= BASE_URL ?>invoices/show/<?= $invoice['id'] ?>" class="text-decoration-none no-print"><?= htmlspecialchars($invoice['invoice_no']) ?></a>
                            <span class="d-none d-print-inline"><?= htmlspecialchars($invoice['invoice_no']) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold ps-0">Payment Date:</td>
                        <td class="text-end pe-0"><?= date('M d, Y', strtotime($receipt['payment_date'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold ps-0">Payment Mode:</td>
                        <td class="text-end pe-0">
                            <?php
                            $modeColors = [
                                'Cash'          => 'success',
                                'Cheque'        => 'primary',
                                'Bank Transfer' => 'info',
                                'Credit Card'   => 'warning',
                            ];
                            $modeColor = $modeColors[$receipt['payment_mode']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $modeColor ?> badge-mode"><?= htmlspecialchars($receipt['payment_mode']) ?></span>
                        </td>
                    </tr>
                    <?php if (!empty($receipt['reference_number'])): ?>
                    <tr>
                        <td class="text-muted fw-semibold ps-0">Reference:</td>
                        <td class="text-end pe-0"><?= htmlspecialchars($receipt['reference_number']) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Amount Received -->
    <div class="row justify-content-end mb-4">
        <div class="col-md-5">
            <table class="table table-sm table-borderless mb-0" style="font-size:.95rem;">
                <tr>
                    <td class="text-muted fw-semibold">Invoice Total</td>
                    <td class="text-end"><?= formatMoney($invoice['grand_total']) ?></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Total Paid (incl. this)</td>
                    <td class="text-end text-success"><?= formatMoney($total_paid) ?></td>
                </tr>
                <?php if ($balance_due > 0): ?>
                <tr>
                    <td class="text-muted fw-semibold">Balance Due</td>
                    <td class="text-end text-danger"><?= formatMoney($balance_due) ?></td>
                </tr>
                <?php endif; ?>
                <tr style="border-top: 2px solid #3a3f51;">
                    <td class="fw-bold rec-accent fs-5 pt-2">AMOUNT RECEIVED</td>
                    <td class="text-end fw-bold rec-accent fs-5 pt-2"><?= formatMoney($receipt['amount_paid']) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Notes -->
    <?php if (!empty($receipt['notes'])): ?>
    <div class="bg-light rounded p-3 mb-4">
        <h6 class="fw-bold mb-1 text-uppercase small text-muted">Notes</h6>
        <p class="mb-0" style="white-space: pre-line;"><?= htmlspecialchars($receipt['notes']) ?></p>
    </div>
    <?php endif; ?>

    <!-- Banking Details -->
    <?php if (!empty($bankDetails)): ?>
    <div class="mb-4 p-3 rounded" style="background:#f8f9fa; border-left:4px solid #3a3f51;">
        <h6 class="fw-bold mb-2">BANKING DETAILS</h6>
        <div class="text-muted" style="font-size:.85rem; white-space: pre-line;"><?= htmlspecialchars($bankDetails) ?></div>
    </div>
    <?php endif; ?>

    <!-- Signatures -->
    <div class="row mt-5 mb-4">
        <div class="col-6 text-center">
            <div style="position:relative;display:inline-block;min-height:80px;">
                <?php if (!empty($settings['company_signature'])): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_signature'] ?>" alt="Signature"
                         style="max-height:60px;max-width:160px;object-fit:contain;display:block;margin:0 auto 4px;">
                <?php endif; ?>
                <?php if (!empty($settings['company_stamp'])): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_stamp'] ?>" alt="Stamp"
                         style="max-height:70px;max-width:70px;object-fit:contain;position:absolute;bottom:18px;right:-10px;opacity:.88;">
                <?php endif; ?>
            </div>
            <div class="rec-sig-line d-inline-block">
                Authorized Signature<br>
                <small class="text-muted"><?= htmlspecialchars($companyName) ?></small>
            </div>
        </div>
        <div class="col-6 text-center">
            <div style="min-height:80px;"></div>
            <div class="rec-sig-line d-inline-block">
                Received By<br>
                <small class="text-muted"><?= htmlspecialchars($lead['lead_name'] ?? '') ?></small>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="rec-footer-bar mt-4">
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
    const element = document.getElementById('receipt-content');
    const buttons = document.querySelectorAll('.no-print');
    buttons.forEach(b => b.style.display = 'none');

    html2canvas(element, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.85);
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
        const pageHeight = pdf.internal.pageSize.getHeight();

        if (pdfHeight <= pageHeight) {
            pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
        } else {
            let position = 0;
            let remaining = pdfHeight;
            while (remaining > 0) {
                pdf.addImage(imgData, 'JPEG', 0, position, pdfWidth, pdfHeight);
                remaining -= pageHeight;
                position -= pageHeight;
                if (remaining > 0) pdf.addPage();
            }
        }

        pdf.save('Receipt_<?= $receipt['receipt_no'] ?>.pdf');
        buttons.forEach(b => b.style.display = '');
    });
}
</script>
