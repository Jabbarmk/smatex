<style>
/* ── Screen ── */
@media screen {
    .inv-wrapper { max-width: 860px; margin: 0 auto; }
}

/* ── Base styles ── */
.inv-wrapper        { font-family: 'Inter', sans-serif; font-size: .9rem; }
.inv-accent         { color: #e8602c; }
.inv-accent-line    { border-top: 3px solid #e8602c; margin-bottom: 1rem; }
.inv-table          { width: 100%; border-collapse: collapse; }
.inv-table thead    { background: #3a3f51; color: #fff; }
.inv-table thead th { font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; padding: .5rem .9rem; font-weight: 600; }
.inv-table tbody td { padding: .5rem .9rem; font-size: .84rem; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.inv-table tbody tr:last-child td { border-bottom: none; }
.inv-bank-box  { background: #f8f9fa; border-radius: 6px; border-left: 4px solid #3a3f51; padding: .9rem 1rem; }
.inv-terms-box { border-top: 1px solid #e0e0e0; padding-top: .8rem; }
.inv-footer-bar { border-top: 1px solid #ddd; padding-top: .55rem; text-align: center; color: #999; font-size: .72rem; }
.inv-sig-line  { border-top: 1px solid #555; width: 190px; margin-top: 48px; padding-top: 5px; text-align: center; font-size: .82rem; color: #555; }

/* Two-column row — works on screen AND in print */
.inv-two-col      { display: table; width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 10px 0; }
.inv-col-left,
.inv-col-right    { display: table-cell; vertical-align: top; width: 50%; }

/* Info boxes */
.inv-infobox       { background: #f8f9fa; border-radius: 6px; padding: .7rem .9rem; height: 100%; box-sizing: border-box; }
.inv-infobox-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; margin-bottom: .4rem; }

/* Detail key/value table */
.inv-detail-tbl       { width: 100%; border-collapse: collapse; font-size: .84rem; }
.inv-detail-tbl td    { padding: 2px 0; }
.inv-dk               { color: #9ca3af; font-weight: 600; width: 46%; white-space: nowrap; }
.inv-dv               { color: #111827; text-align: right; }

/* Totals table */
.inv-totals-tbl       { width: 100%; border-collapse: collapse; font-size: .92rem; }
.inv-totals-tbl td    { padding: 4px 0; }
.inv-totals-tbl .inv-dk { color: #6b7280; }

/* ── Print ── */
@media print {
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    .no-print, .sidebar, nav, .sidebar-overlay, .topbar { display: none !important; }
    body, html { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    .main-content { margin: 0 !important; padding: 0 !important; background: #fff !important; }
    .inv-wrapper { box-shadow: none !important; border: none !important;
                  max-width: 100% !important; padding: 12mm 13mm !important; margin: 0 !important; }
    a[href]:after { content: none !important; }

    .inv-wrapper .mb-4 { margin-bottom: .8rem !important; }
    .inv-wrapper .mb-3 { margin-bottom: .6rem !important; }
    .inv-wrapper .mt-5 { margin-top: 1.2rem !important; }
    .inv-wrapper .p-3  { padding: .6rem !important; }

    .inv-header-block  { break-inside: avoid; page-break-inside: avoid; }
    .inv-for-block     { break-inside: avoid; page-break-inside: avoid; }
    .inv-totals-block  { break-inside: avoid; page-break-inside: avoid; }
    .inv-bank-box      { break-inside: avoid; page-break-inside: avoid; }
    .inv-sig-block     { break-inside: avoid; page-break-inside: avoid; }
    .inv-footer-bar    { break-inside: avoid; page-break-inside: avoid; }
    .inv-table thead   { break-after:  avoid; page-break-after:  avoid; }
    .inv-table tbody tr{ break-inside: avoid; page-break-inside: avoid; }
    .inv-terms-box     { break-inside: auto; }
    .inv-items-block   { break-inside: auto; }
    .inv-payment-block { break-inside: avoid; page-break-inside: avoid; }

    @page { size: A4 portrait; margin: 0; }
}
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print flex-wrap">
    <button onclick="printInvoice()" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Print / Save PDF</button>
    <button onclick="downloadPDF()" class="btn btn-primary"><i class="fas fa-download me-1"></i> Download PDF</button>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#currencyModal"><i class="fas fa-exchange-alt me-1"></i> Convert &amp; Export</button>
    <?php if (!$invoice['is_draft'] && $invoice['status'] !== 'Paid'): ?>
        <a href="<?= BASE_URL ?>receipts/create/<?= $invoice['id'] ?>" class="btn btn-success"><i class="fas fa-money-bill-wave me-1"></i> Record Payment</a>
    <?php endif; ?>
    <?php if ($invoice['is_draft']): ?>
        <a href="<?= BASE_URL ?>invoices/promote/<?= $invoice['id'] ?>" class="btn btn-success"
           onclick="return confirm('Convert this draft to a real invoice? A new INV number will be assigned.')"
           title="Assign a real invoice number and move to Invoices">
            <i class="fas fa-check-circle me-1"></i> Promote to Invoice
        </a>
    <?php endif; ?>
    <button type="button" class="btn btn-outline-secondary" onclick="toggleSigStamp(this)" id="sigToggleBtn" title="Toggle signature &amp; stamp visibility">
        <i class="fas fa-signature me-1"></i> <span id="sigToggleLbl">Hide Sign &amp; Stamp</span>
    </button>
    <a href="<?= BASE_URL ?>invoices/edit/<?= $invoice['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</a>
    <a href="<?= BASE_URL ?>invoices" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<!-- Currency Conversion Modal -->
<div class="modal fade" id="currencyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-exchange-alt me-2 text-success"></i>Convert &amp; Export</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-0">
                <p class="text-muted small mb-3">Choose a target currency and enter the exchange rate. All amounts will be converted before export.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Target Currency</label>
                    <select class="form-select" id="convertCurrency">
                        <option value="USD" data-symbol="$"        data-label="USD">US Dollar (USD)</option>
                        <option value="INR" data-symbol="&#8377;"  data-label="INR">Indian Rupee (INR)</option>
                        <option value="EUR" data-symbol="&#8364;"  data-label="EUR">Euro (EUR)</option>
                        <option value="GBP" data-symbol="&#163;"   data-label="GBP">British Pound (GBP)</option>
                        <option value="SAR" data-symbol="SAR "     data-label="SAR">Saudi Riyal (SAR)</option>
                        <option value="SGD" data-symbol="S$"       data-label="SGD">Singapore Dollar (SGD)</option>
                        <option value="AED" data-symbol="AED "     data-label="AED">UAE Dirham (AED)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Exchange Rate</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted" id="rate-prefix">1 <?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?> =</span>
                        <input type="number" class="form-control" id="exchangeRate" value="1" min="0.0001" step="0.0001" placeholder="e.g. 23.04">
                        <span class="input-group-text fw-semibold text-success" id="rate-suffix">USD</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-outline-secondary me-auto" id="btnResetCurrency"><i class="fas fa-undo me-1"></i>Reset</button>
                <button type="button" class="btn btn-success px-4" id="btnApplyConvert"><i class="fas fa-file-pdf me-1"></i> Convert &amp; Print PDF</button>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Document -->
<div class="inv-wrapper bg-white p-4 rounded shadow-sm" id="invoice-content">

    <?php
    if (!isset($settings)) {
        require_once 'app/models/SettingsModel.php';
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();
    }
    $companyName    = $settings['company_name']    ?? 'Company';
    $companyAddress = $settings['company_address'] ?? '';
    $companyEmail   = $settings['company_email']   ?? '';
    $companyPhone   = $settings['company_phone']   ?? '';
    $companyWebsite = $settings['company_website'] ?? '';
    $companyTrn     = $settings['company_trn']     ?? '';
    $bankDetails    = $settings['bank_details']    ?? '';
    $invoiceTerms   = $settings['invoice_terms']   ?? '';
    $invoiceFooter  = $settings['invoice_footer']  ?? '';
    ?>

    <!-- ===== HEADER: Logo + Company | DSO + INVOICE title ===== -->
    <div class="inv-header-block mb-3" style="display:table;width:100%;table-layout:fixed;border-collapse:separate;border-spacing:10px 0;">
        <div style="display:table-cell;vertical-align:top;width:66%;">
            <div style="display:flex;align-items:center;gap:12px;">
                <?php if (!empty($settings['company_logo'])): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height:65px;max-width:120px;object-fit:contain;">
                <?php endif; ?>
                <div>
                    <div style="font-size:1.05rem;font-weight:700;color:#1a1a2e;"><?= htmlspecialchars($companyName) ?></div>
                    <?php if ($companyTrn): ?>
                        <div style="font-size:.75rem;font-weight:700;color:#e8602c;">TRN: <?= htmlspecialchars($companyTrn) ?></div>
                    <?php endif; ?>
                    <div style="font-size:.75rem;color:#6b7280;"><?= nl2br(htmlspecialchars($companyAddress)) ?></div>
                    <div style="font-size:.75rem;color:#6b7280;">
                        <?= htmlspecialchars($companyEmail) ?><?php if ($companyPhone): ?> &nbsp;|&nbsp; <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:table-cell;vertical-align:top;text-align:right;width:34%;">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:55px;width:auto;display:block;margin-left:auto;margin-bottom:5px;">
            <div style="font-size:2rem;font-weight:800;color:#3a3f51;letter-spacing:1px;line-height:1.1;">
                <?= $invoice['is_draft'] ? '<span style="color:#6c757d;">DRAFT</span>' : 'INVOICE' ?>
            </div>
            <div style="font-weight:700;color:#e8602c;font-size:.95rem;"><?= htmlspecialchars($invoice['invoice_no']) ?></div>
            <div style="font-size:.78rem;color:#6b7280;">Date: <?= date('d M Y', strtotime($invoice['created_at'])) ?></div>
        </div>
    </div>

    <!-- Accent line -->
    <div class="inv-accent-line mb-4"></div>

    <!-- ===== BILL TO + INVOICE DETAILS ===== -->
    <div class="inv-for-block inv-two-col mb-3">
        <div class="inv-col-left">
            <div class="inv-infobox">
                <p class="inv-infobox-label">Bill To</p>
                <div class="fw-bold" style="font-size:1rem;"><?= htmlspecialchars($lead['lead_name'] ?: $invoice['client_details']) ?></div>
                <?php if (!empty($lead['company_name'])): ?>
                    <div class="text-muted"><?= htmlspecialchars($lead['company_name']) ?></div>
                <?php endif; ?>
                <?php if (!empty($lead['email'])): ?>
                    <div class="text-muted" style="font-size:.82rem;"><?= htmlspecialchars($lead['email']) ?></div>
                <?php endif; ?>
                <?php if (!empty($lead['phone'])): ?>
                    <div class="text-muted" style="font-size:.82rem;"><?= htmlspecialchars($lead['phone']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="inv-col-right">
            <div class="inv-infobox">
                <p class="inv-infobox-label">Invoice Details</p>
                <table class="inv-detail-tbl">
                    <tr><td class="inv-dk">Invoice #</td><td class="inv-dv fw-bold"><?= htmlspecialchars($invoice['invoice_no']) ?></td></tr>
                    <tr><td class="inv-dk">Currency</td><td class="inv-dv" id="inv-currency-label"><?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?></td></tr>
                    <tr><td class="inv-dk">Due Date</td><td class="inv-dv"><?= date('d M Y', strtotime($invoice['due_date'])) ?></td></tr>
                    <tr><td class="inv-dk">Status</td>
                        <td class="inv-dv">
                            <span style="display:inline-block;padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:600;
                                background:<?= $invoice['status']=='Paid'?'#d1fae5':($invoice['status']=='Partial'?'#fef3c7':'#fee2e2') ?>;
                                color:<?= $invoice['status']=='Paid'?'#065f46':($invoice['status']=='Partial'?'#92400e':'#991b1b') ?>;">
                                <?= $invoice['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php if (!empty($invoice['payment_terms'])): ?>
                    <tr><td class="inv-dk">Payment Terms</td><td class="inv-dv"><?= htmlspecialchars($invoice['payment_terms']) ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== ITEMS TABLE ===== -->
    <div class="inv-items-block mb-3">
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
                    <td class="text-end conv-amount" data-amount="<?= $item['unit_price'] ?>"><?= formatMoney($item['unit_price'], false) ?></td>
                    <td class="text-end conv-amount" data-amount="<?= $item['line_total'] ?>"><?= formatMoney($item['line_total'], false) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== TOTALS ===== -->
    <div class="inv-totals-block mb-3" style="display:flex;justify-content:flex-end;">
        <table class="inv-totals-tbl" style="width:300px;">
            <tr>
                <td class="inv-dk fw-semibold">Subtotal</td>
                <td class="text-end conv-amount" data-amount="<?= $invoice['subtotal'] ?>"><?= formatMoney($invoice['subtotal']) ?></td>
            </tr>
            <?php if (($invoice['discount'] ?? 0) > 0): ?>
            <tr>
                <td class="inv-dk fw-semibold">Discount</td>
                <td class="text-end text-danger">-<span class="conv-amount" data-amount="<?= $invoice['discount'] ?>"><?= formatMoney($invoice['discount']) ?></span></td>
            </tr>
            <?php endif; ?>
            <?php
            $inv_tax_pct = $invoice['tax_percentage'] ?? 0;
            if ($invoice['vat_total'] > 0 || $inv_tax_pct > 0):
                $tax_label = ($inv_tax_pct > 0) ? "Tax (" . $inv_tax_pct . "%)" : "VAT (5%)";
            ?>
            <tr>
                <td class="inv-dk fw-semibold"><?= $tax_label ?></td>
                <td class="text-end conv-amount" data-amount="<?= $invoice['vat_total'] ?>"><?= formatMoney($invoice['vat_total']) ?></td>
            </tr>
            <?php endif; ?>
            <tr style="border-top:2px solid #3a3f51;">
                <td class="fw-bold pt-2" style="color:#e8602c;font-size:1rem;">AMOUNT DUE</td>
                <td class="text-end fw-bold pt-2 conv-amount" style="color:#e8602c;font-size:1rem;" data-amount="<?= $invoice['grand_total'] ?>"><?= formatMoney($invoice['grand_total']) ?></td>
            </tr>
        </table>
    </div>

    <!-- ===== PAYMENT HISTORY (if any) ===== -->
    <?php
    require_once 'app/models/ReceiptModel.php';
    $receiptModel = new ReceiptModel();
    $receipts     = $receiptModel->getByInvoiceId($invoice['id']);
    $totalPaid    = $receiptModel->getTotalPaid($invoice['id']);
    $balanceDue   = $invoice['grand_total'] - $totalPaid;
    ?>

    <?php if (!empty($receipts)): ?>
    <div class="inv-payment-block mb-3" style="background:#f8f9fa;border-radius:6px;padding:.7rem .9rem;">
        <h6 class="fw-bold mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;">Payment History</h6>
        <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
            <thead>
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <th style="padding:3px 0;color:#6b7280;font-weight:600;">Date</th>
                    <th style="padding:3px 0;color:#6b7280;font-weight:600;">Reference</th>
                    <th style="padding:3px 0;color:#6b7280;font-weight:600;">Mode</th>
                    <th style="padding:3px 0;color:#6b7280;font-weight:600;text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($receipts as $receipt): ?>
                <tr>
                    <td style="padding:3px 0;"><?= date('d M Y', strtotime($receipt['payment_date'])) ?></td>
                    <td style="padding:3px 0;"><?= htmlspecialchars($receipt['receipt_no']) ?></td>
                    <td style="padding:3px 0;"><?= htmlspecialchars($receipt['payment_mode']) ?></td>
                    <td style="padding:3px 0;text-align:right;color:#065f46;font-weight:600;" class="conv-amount" data-amount="<?= $receipt['amount_paid'] ?>"><?= formatMoney($receipt['amount_paid']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid #d1d5db;font-weight:700;">
                    <td colspan="3" style="padding:4px 0;text-align:right;">Total Paid:</td>
                    <td style="padding:4px 0;text-align:right;color:#065f46;" class="conv-amount" data-amount="<?= $totalPaid ?>"><?= formatMoney($totalPaid) ?></td>
                </tr>
                <?php if ($balanceDue > 0): ?>
                <tr style="font-weight:700;">
                    <td colspan="3" style="padding:2px 0;text-align:right;">Balance Due:</td>
                    <td style="padding:2px 0;text-align:right;color:#991b1b;" class="conv-amount" data-amount="<?= $balanceDue ?>"><?= formatMoney($balanceDue) ?></td>
                </tr>
                <?php endif; ?>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>

    <!-- ===== BANKING DETAILS ===== -->
    <?php if (!empty($bankDetails)): ?>
    <div class="inv-bank-box mb-3">
        <h6 class="fw-bold mb-2">BANKING DETAILS FOR PAYMENT</h6>
        <div class="text-muted" style="font-size:.85rem; white-space: pre-line;"><?= htmlspecialchars($bankDetails) ?></div>
    </div>
    <?php endif; ?>

    <!-- ===== TERMS & CONDITIONS ===== -->
    <?php if (!empty($invoice['payment_terms']) || !empty($invoiceTerms)): ?>
    <div class="inv-terms-box mb-4">
        <h6 class="fw-bold text-uppercase small mb-2">Terms &amp; Conditions</h6>
        <div class="text-muted small" style="white-space: pre-line;"><?= htmlspecialchars(!empty($invoiceTerms) ? $invoiceTerms : $invoice['payment_terms']) ?></div>
    </div>
    <?php endif; ?>

    <!-- ===== SIGNATURES ===== -->
    <div class="inv-sig-block" style="display:table;width:100%;margin-top:2rem;margin-bottom:1rem;">
        <div style="display:table-cell;width:50%;text-align:center;padding:0 1rem;">
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
            <div class="inv-sig-line" style="display:inline-block;">
                Authorized Signature<br>
                <small style="color:#6b7280;"><?= htmlspecialchars($companyName) ?></small>
            </div>
        </div>
        <div style="display:table-cell;width:50%;text-align:center;padding:0 1rem;">
            <div style="min-height:80px;"></div>
            <div class="inv-sig-line" style="display:inline-block;">
                Customer Acceptance<br>
                <small style="color:#6b7280;"><?= htmlspecialchars($lead['lead_name'] ?: ($lead['company_name'] ?? '')) ?></small>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
window.jsPDF = window.jspdf.jsPDF;

const invoicePdfName = <?= json_encode(preg_replace('/[^A-Za-z0-9 _-]/', '_', trim(trim($lead['lead_name'] ?: ($lead['company_name'] ?? 'Client')) . '-' . ($invoice['is_draft'] ? 'Draft' : 'Invoice') . '-' . $invoice['invoice_no']))) ?>;

function printInvoice() {
    const prev = document.title;
    document.title = invoicePdfName;
    window.addEventListener('afterprint', () => { document.title = prev; }, { once: true });
    window.print();
}

function downloadPDF() {
    const element = document.getElementById('invoice-content');
    const buttons = document.querySelectorAll('.no-print');
    buttons.forEach(b => b.style.display = 'none');

    html2canvas(element, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.85);
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth  = pdf.internal.pageSize.getWidth();
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
                position  -= pageHeight;
                if (remaining > 0) pdf.addPage();
            }
        }

        pdf.save(invoicePdfName + '.pdf');
        buttons.forEach(b => b.style.display = '');
    });
}

let sigVisible = true;
function toggleSigStamp(btn) {
    const block = document.querySelector('.inv-sig-block');
    if (!block) return;
    sigVisible = !sigVisible;
    block.style.display = sigVisible ? '' : 'none';
    document.getElementById('sigToggleLbl').textContent = sigVisible ? 'Hide Sign & Stamp' : 'Show Sign & Stamp';
}

// ── Convert & Export ────────────────────────────────────────────────────
const origInvSymbol = <?= json_encode($settings['currency_symbol'] ?? 'AED') ?>;
let invConverted = false;

function fmtConvertedInv(amount, symbol, decimals) {
    return symbol + Number(amount).toLocaleString('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const currSel  = document.getElementById('convertCurrency');
    const rateSufx = document.getElementById('rate-suffix');
    if (!currSel) return;

    currSel.addEventListener('change', function () {
        rateSufx.textContent = this.options[this.selectedIndex].dataset.label;
    });

    document.getElementById('btnApplyConvert').addEventListener('click', function () {
        const opt    = currSel.options[currSel.selectedIndex];
        const symbol = opt.dataset.symbol;
        const label  = opt.dataset.label;
        const rate   = parseFloat(document.getElementById('exchangeRate').value) || 1;

        document.querySelectorAll('.conv-amount').forEach(el => {
            const orig = parseFloat(el.dataset.amount) || 0;
            el.textContent = fmtConvertedInv(orig * rate, symbol, 2);
        });

        const lbl = document.getElementById('inv-currency-label');
        if (lbl) lbl.textContent = label + '  (1 ' + origInvSymbol + ' = ' + rate + ' ' + label + ')';

        invConverted = true;
        bootstrap.Modal.getInstance(document.getElementById('currencyModal')).hide();
        setTimeout(() => printInvoice(), 350);
    });

    document.getElementById('btnResetCurrency').addEventListener('click', function () {
        if (invConverted) location.reload();
    });
});
</script>
