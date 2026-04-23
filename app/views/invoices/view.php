<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .content-wrapper, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .topbar, nav { display: none !important; }
    .inv-wrapper { box-shadow: none !important; border: none !important; }
    a[href]:after { content: none !important; }
}
@media screen {
    .inv-wrapper { max-width: 900px; margin: 0 auto; }
}
.inv-wrapper { font-family: 'Inter', sans-serif; }
.inv-accent { color: #e8602c; }
.inv-accent-line { border-top: 3px solid #e8602c; }
.inv-table thead { background: #3a3f51; color: #fff; }
.inv-table thead th { font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; padding: .75rem 1rem; font-weight: 600; }
.inv-table tbody td { padding: .75rem 1rem; font-size: .875rem; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.inv-table tbody tr:last-child td { border-bottom: none; }
.inv-bank-box { background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3a3f51; padding: 1.25rem; }
.inv-terms-box { border-top: 1px solid #e0e0e0; padding-top: 1rem; }
.inv-footer-bar { border-top: 1px solid #ddd; padding-top: .75rem; text-align: center; color: #999; font-size: .75rem; }
.inv-sig-line { border-top: 1px solid #555; width: 200px; margin-top: 60px; padding-top: 5px; text-align: center; font-size: .85rem; color: #555; }
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print">
    <button onclick="window.print()" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Print / Save PDF</button>
    <button onclick="downloadPDF()" class="btn btn-primary"><i class="fas fa-download me-1"></i> Download PDF</button>
    <?php if ($invoice['status'] !== 'Paid'): ?>
        <a href="<?= BASE_URL ?>receipts/create/<?= $invoice['id'] ?>" class="btn btn-success"><i class="fas fa-money-bill-wave me-1"></i> Record Payment</a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>invoices/edit/<?= $invoice['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</a>
    <a href="<?= BASE_URL ?>invoices" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<!-- Invoice Document -->
<div class="inv-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="invoice-content">

    <?php
    if (!isset($settings)) {
        require_once 'app/models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();
    }
    $companyName = $settings['company_name'] ?? 'Company';
    $companyAddress = $settings['company_address'] ?? '';
    $companyEmail = $settings['company_email'] ?? '';
    $companyPhone = $settings['company_phone'] ?? '';
    $companyWebsite = $settings['company_website'] ?? '';
    $companyTrn = $settings['company_trn'] ?? '';
    $bankDetails = $settings['bank_details'] ?? '';
    $invoiceTerms = $settings['invoice_terms'] ?? '';
    $invoiceFooter = $settings['invoice_footer'] ?? '';
    ?>

    <!-- ===== HEADER: Logo + Company + INVOICE title ===== -->
    <div class="row align-items-center mb-3">
        <div class="col-md-7 d-flex align-items-center gap-3">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height: 70px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($companyName) ?></h4>
                <?php if ($companyTrn): ?>
                    <small class="inv-accent fw-bold">TRN: <?= htmlspecialchars($companyTrn) ?></small><br>
                <?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small><br>
                <small class="text-muted">
                    <?= htmlspecialchars($companyEmail) ?>
                    <?php if ($companyPhone): ?> | <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
                </small>
            </div>
        </div>
        <div class="col-md-5 text-end">
            <h1 class="fw-bold text-uppercase mb-1" style="font-size: 2.2rem; color: #3a3f51;">INVOICE</h1>
            <h5 class="fw-bold inv-accent mb-0"><?= $invoice['invoice_no'] ?></h5>
            <small class="text-muted">Date: <?= date('M d, Y', strtotime($invoice['created_at'])) ?></small>
        </div>
    </div>

    <!-- Accent line -->
    <div class="inv-accent-line mb-4"></div>

    <!-- ===== BILL TO + INVOICE DETAILS ===== -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="bg-light rounded p-3">
                <p class="text-uppercase text-muted fw-bold small mb-2">Bill To</p>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($lead['lead_name'] ?: $invoice['client_details']) ?></h5>
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
                <p class="text-uppercase text-muted fw-bold small mb-2">Invoice Details</p>
                <table class="table table-sm table-borderless mb-0" style="font-size:.875rem;">
                    <tr><td class="text-muted fw-semibold ps-0">Invoice #:</td><td class="text-end pe-0 fw-bold"><?= $invoice['invoice_no'] ?></td></tr>
                    <tr><td class="text-muted fw-semibold ps-0">Currency:</td><td class="text-end pe-0"><?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?></td></tr>
                    <tr><td class="text-muted fw-semibold ps-0">Due Date:</td><td class="text-end pe-0"><?= date('M d, Y', strtotime($invoice['due_date'])) ?></td></tr>
                    <tr><td class="text-muted fw-semibold ps-0">Status:</td><td class="text-end pe-0"><span class="badge bg-<?= $invoice['status'] == 'Paid' ? 'success' : ($invoice['status'] == 'Partial' ? 'warning' : 'danger') ?>"><?= $invoice['status'] ?></span></td></tr>
                    <?php if (!empty($invoice['payment_terms'])): ?>
                    <tr><td class="text-muted fw-semibold ps-0">Payment Terms:</td><td class="text-end pe-0"><?= htmlspecialchars($invoice['payment_terms']) ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== ITEMS TABLE ===== -->
    <div class="table-responsive mb-4">
        <table class="table inv-table mb-0">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Product / Description</th>
                    <th class="text-center" style="width:80px;">Qty</th>
                    <th class="text-end" style="width:130px;">Unit Price</th>
                    <th class="text-end" style="width:140px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $i => $item): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td>
                        <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                        <?php if(!empty($item['description'])): ?>
                            <div class="text-muted small"><?= htmlspecialchars($item['description']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?= number_format($item['qty'], 2) ?></td>
                    <td class="text-end"><?= formatMoney($item['unit_price'], false) ?></td>
                    <td class="text-end"><?= formatMoney($item['line_total'], false) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== TOTALS ===== -->
    <div class="row justify-content-end mb-4">
        <div class="col-md-5">
            <table class="table table-sm table-borderless mb-0" style="font-size: .95rem;">
                <tr>
                    <td class="text-muted fw-semibold">Subtotal</td>
                    <td class="text-end"><?= formatMoney($invoice['subtotal']) ?></td>
                </tr>
                <?php if (($invoice['discount'] ?? 0) > 0): ?>
                <tr>
                    <td class="text-muted fw-semibold">Discount</td>
                    <td class="text-end text-danger">-<?= formatMoney($invoice['discount']) ?></td>
                </tr>
                <?php endif; ?>
                <?php
                $inv_tax_pct = $invoice['tax_percentage'] ?? 0;
                if ($invoice['vat_total'] > 0 || $inv_tax_pct > 0):
                    $tax_label = ($inv_tax_pct > 0) ? "Tax (" . $inv_tax_pct . "%)" : "VAT (5%)";
                ?>
                <tr>
                    <td class="text-muted fw-semibold"><?= $tax_label ?></td>
                    <td class="text-end"><?= formatMoney($invoice['vat_total']) ?></td>
                </tr>
                <?php endif; ?>
                <tr style="border-top: 2px solid #3a3f51;">
                    <td class="fw-bold inv-accent fs-5 pt-2">AMOUNT DUE</td>
                    <td class="text-end fw-bold inv-accent fs-5 pt-2"><?= formatMoney($invoice['grand_total']) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ===== PAYMENT HISTORY (if any) ===== -->
    <?php
    require_once 'app/models/ReceiptModel.php';
    $receiptModel = new ReceiptModel();
    $receipts = $receiptModel->getByInvoiceId($invoice['id']);
    $totalPaid = $receiptModel->getTotalPaid($invoice['id']);
    $balanceDue = $invoice['grand_total'] - $totalPaid;
    ?>

    <?php if (!empty($receipts)): ?>
    <div class="mb-4 p-3 bg-light rounded">
        <h6 class="fw-bold mb-3"><i class="fas fa-history me-2 text-muted"></i>Payment History</h6>
        <table class="table table-sm mb-0" style="font-size:.85rem;">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Mode</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($receipts as $receipt): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($receipt['payment_date'])) ?></td>
                    <td><?= $receipt['receipt_no'] ?></td>
                    <td><?= $receipt['payment_mode'] ?></td>
                    <td class="text-end text-success fw-semibold"><?= formatMoney($receipt['amount_paid']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold" style="border-top:2px solid #ddd;">
                    <td colspan="3" class="text-end">Total Paid:</td>
                    <td class="text-end text-success"><?= formatMoney($totalPaid) ?></td>
                </tr>
                <?php if ($balanceDue > 0): ?>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Balance Due:</td>
                    <td class="text-end text-danger"><?= formatMoney($balanceDue) ?></td>
                </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>

    <!-- ===== BANKING DETAILS ===== -->
    <?php if (!empty($bankDetails)): ?>
    <div class="inv-bank-box mb-4">
        <h6 class="fw-bold mb-2">BANKING DETAILS FOR PAYMENT</h6>
        <div class="text-muted" style="font-size:.85rem; white-space: pre-line;"><?= htmlspecialchars($bankDetails) ?></div>
    </div>
    <?php endif; ?>

    <!-- ===== TERMS & CONDITIONS ===== -->
    <?php if (!empty($invoice['payment_terms']) || !empty($invoiceTerms)): ?>
    <div class="inv-terms-box mb-4">
        <h6 class="fw-bold text-uppercase small mb-2">Terms & Conditions</h6>
        <div class="text-muted small" style="white-space: pre-line;"><?= htmlspecialchars(!empty($invoiceTerms) ? $invoiceTerms : $invoice['payment_terms']) ?></div>
    </div>
    <?php endif; ?>

    <!-- ===== SIGNATURES ===== -->
    <div class="row mt-5 mb-4">
        <div class="col-6 text-center">
            <div class="inv-sig-line d-inline-block">
                Authorized Signature<br>
                <small class="text-muted"><?= htmlspecialchars($companyName) ?></small>
            </div>
        </div>
        <div class="col-6 text-center">
            <div class="inv-sig-line d-inline-block">
                Customer Acceptance<br>
                <small class="text-muted"><?= htmlspecialchars($lead['lead_name'] ?: ($lead['company_name'] ?? '')) ?></small>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER BAR ===== -->
    <div class="inv-footer-bar mt-4">
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
    const element = document.getElementById('invoice-content');
    const buttons = document.querySelectorAll('.no-print');
    buttons.forEach(b => b.style.display = 'none');

    html2canvas(element, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.85);
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        // Handle multi-page if content is tall
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

        pdf.save('Invoice_<?= $invoice['invoice_no'] ?>.pdf');
        buttons.forEach(b => b.style.display = '');
    });
}
</script>
