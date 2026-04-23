<style>
/* ── Screen ── */
@media screen {
    .qt-wrapper { max-width: 860px; margin: 0 auto; }
}

/* ── Base styles ── */
.qt-wrapper        { font-family: 'Inter', sans-serif; font-size: .9rem; }
.qt-accent         { color: #e8602c; }
.qt-accent-line    { border-top: 3px solid #e8602c; margin-bottom: 1rem; }
.qt-table          { width: 100%; border-collapse: collapse; }
.qt-table thead    { background: #3a3f51; color: #fff; }
.qt-table thead th { font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; padding: .5rem .9rem; font-weight: 600; }
.qt-table tbody td { padding: .5rem .9rem; font-size: .84rem; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.qt-table tbody tr:last-child td { border-bottom: none; }
.qt-bank-box  { background: #f8f9fa; border-radius: 6px; border-left: 4px solid #3a3f51; padding: .9rem 1rem; }
.qt-india-box { background: #fff8f0; border-radius: 6px; border-left: 4px solid #e8602c; padding: .9rem 1rem; }
.qt-terms-box { border-top: 1px solid #e0e0e0; padding-top: .8rem; }
.qt-footer-bar { border-top: 1px solid #ddd; padding-top: .55rem; text-align: center; color: #999; font-size: .72rem; }
.qt-sig-line  { border-top: 1px solid #555; width: 190px; margin-top: 48px; padding-top: 5px; text-align: center; font-size: .82rem; color: #555; }

/* ── Two-column row — works on screen AND in print ── */
.qt-two-col       { display: table; width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 10px 0; }
.qt-col-left,
.qt-col-right,
.qt-col-logo      { display: table-cell; vertical-align: top; }
.qt-col-left      { width: 50%; }
.qt-col-right     { width: 50%; }
.qt-col-logo      { width: 60%; }
.qt-two-col > div:last-child { width: 40%; }

/* Info boxes */
.qt-infobox       { background: #f8f9fa; border-radius: 6px; padding: .7rem .9rem; height: 100%; box-sizing: border-box; }
.qt-infobox-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; margin-bottom: .4rem; }

/* Detail key/value table */
.qt-detail-tbl       { width: 100%; border-collapse: collapse; font-size: .84rem; }
.qt-detail-tbl td    { padding: 2px 0; }
.qt-dk               { color: #9ca3af; font-weight: 600; width: 46%; white-space: nowrap; }
.qt-dv               { color: #111827; text-align: right; }

/* Totals table */
.qt-totals-tbl        { width: 100%; border-collapse: collapse; font-size: .92rem; }
.qt-totals-tbl td     { padding: 4px 0; }
.qt-totals-tbl .qt-dk { color: #6b7280; }

/* ── Print ── */
@media print {
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    /* Hide chrome */
    .no-print, .sidebar, nav, .sidebar-overlay, .topbar { display: none !important; }
    body, html { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    .main-content { margin: 0 !important; padding: 0 !important; background: #fff !important; }
    .qt-wrapper { box-shadow: none !important; border: none !important;
                  max-width: 100% !important; padding: 12mm 13mm !important; margin: 0 !important; }
    a[href]:after { content: none !important; }

    /* Tighten spacing so more fits on page 1 */
    .qt-wrapper .mb-4 { margin-bottom: .8rem !important; }
    .qt-wrapper .mb-3 { margin-bottom: .6rem !important; }
    .qt-wrapper .mt-5 { margin-top: 1.2rem !important; }
    .qt-wrapper .p-3  { padding: .6rem !important; }

    /* Blocks that must never be sliced mid-element */
    .qt-header-block  { break-inside: avoid; page-break-inside: avoid; }
    .qt-for-block     { break-inside: avoid; page-break-inside: avoid; }
    .qt-totals-block  { break-inside: avoid; page-break-inside: avoid; }
    .qt-bank-box      { break-inside: avoid; page-break-inside: avoid; }
    .qt-india-box     { break-inside: avoid; page-break-inside: avoid; }
    .qt-sig-block     { break-inside: avoid; page-break-inside: avoid; }
    .qt-footer-bar    { break-inside: avoid; page-break-inside: avoid; }
    .qt-table thead   { break-after:  avoid; page-break-after:  avoid; }
    .qt-table tbody tr{ break-inside: avoid; page-break-inside: avoid; }

    /* Terms can flow across pages — just keep orphan lines tidy */
    .qt-terms-box          { break-inside: auto; }
    .qt-terms-box div      { orphans: 3; widows: 3; }

    /* Items block: thead sticks with first row, whole block can span pages */
    .qt-items-block { break-inside: auto; }

    @page { size: A4 portrait; margin: 0; }
}
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print flex-wrap">
    <button onclick="printClean()" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Save as PDF</button>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#currencyModal"><i class="fas fa-exchange-alt me-1"></i> Convert &amp; Export</button>
    <a href="<?= BASE_URL ?>quotations/edit/<?= $quotation['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</a>
    <a href="<?= BASE_URL ?>quotations" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
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
                        <option value="USD" data-symbol="$" data-label="USD">🇺🇸 US Dollar (USD)</option>
                        <option value="INR" data-symbol="₹" data-label="INR">🇮🇳 Indian Rupee (INR)</option>
                        <option value="EUR" data-symbol="€" data-label="EUR">🇪🇺 Euro (EUR)</option>
                        <option value="GBP" data-symbol="£" data-label="GBP">🇬🇧 British Pound (GBP)</option>
                        <option value="SAR" data-symbol="﷼" data-label="SAR">🇸🇦 Saudi Riyal (SAR)</option>
                        <option value="SGD" data-symbol="S$" data-label="SGD">🇸🇬 Singapore Dollar (SGD)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Exchange Rate</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted" id="rate-prefix">1 <?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?> =</span>
                        <input type="number" class="form-control" id="exchangeRate" value="1" min="0.0001" step="0.0001" placeholder="e.g. 23.04">
                        <span class="input-group-text fw-semibold text-success" id="rate-suffix">USD</span>
                    </div>
                    <div class="text-muted small mt-1" id="rate-hint"></div>
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

<!-- Quotation Document -->
<div class="qt-wrapper bg-white p-4 rounded shadow-sm" id="quotation-content">

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
    ?>

    <!-- ===== HEADER: Logo + Company + QUOTATION title ===== -->
    <div class="qt-header-block qt-two-col mb-3" style="align-items:center;">
        <div class="qt-col-logo" style="vertical-align:middle;">
            <div style="display:flex;align-items:center;gap:14px;">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height:65px;max-width:120px;">
            <?php endif; ?>
            <div>
                <div style="font-size:1.15rem;font-weight:700;color:#1a1a2e;"><?= htmlspecialchars($companyName) ?></div>
                <?php if ($companyTrn): ?>
                    <div style="font-size:.78rem;font-weight:700;color:#e8602c;">TRN: <?= htmlspecialchars($companyTrn) ?></div>
                <?php endif; ?>
                <div style="font-size:.78rem;color:#6b7280;"><?= nl2br(htmlspecialchars($companyAddress)) ?></div>
                <div style="font-size:.78rem;color:#6b7280;">
                    <?= htmlspecialchars($companyEmail) ?><?php if ($companyPhone): ?> &nbsp;|&nbsp; <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
                </div>
            </div>
        </div><!-- /qt-col-logo inner flex -->
        </div><!-- /qt-col-logo -->
        <div style="display:table-cell; vertical-align:middle; text-align:right; width:40%;">
            <div style="font-size:1.9rem;font-weight:800;color:#3a3f51;letter-spacing:1px;line-height:1;">QUOTATION</div>
            <div style="font-size:1rem;font-weight:700;color:#e8602c;margin-top:3px;"><?= $quotation['quotation_no'] ?></div>
            <div style="font-size:.8rem;color:#6b7280;margin-top:2px;">Valid Until: <strong><?= date('d M Y', strtotime($quotation['valid_until'])) ?></strong></div>
        </div>
    </div>

    <!-- Accent line -->
    <div class="qt-accent-line mb-4"></div>

    <!-- ===== QUOTATION FOR + DETAILS (flex — stays one row on screen AND print) ===== -->
    <div class="qt-for-block qt-two-col mb-3">
        <div class="qt-col-left">
            <div class="qt-infobox">
                <p class="qt-infobox-label">Quotation For</p>
                <div class="fw-bold" style="font-size:1rem;"><?= htmlspecialchars($lead['lead_name']) ?></div>
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
        <div class="qt-col-right">
            <div class="qt-infobox">
                <p class="qt-infobox-label">Quotation Details</p>
                <table class="qt-detail-tbl">
                    <tr><td class="qt-dk">Quotation #</td><td class="qt-dv fw-bold"><?= $quotation['quotation_no'] ?></td></tr>
                    <tr><td class="qt-dk">Currency</td><td class="qt-dv" id="qt-currency-label"><?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?></td></tr>
                    <tr><td class="qt-dk">Valid Until</td><td class="qt-dv"><?= date('d M Y', strtotime($quotation['valid_until'])) ?></td></tr>
                    <tr><td class="qt-dk">Status</td>
                        <td class="qt-dv">
                            <span style="display:inline-block;padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:600;
                                background:<?= $quotation['status']=='Approved'?'#d1fae5':($quotation['status']=='Rejected'?'#fee2e2':'#e5e7eb') ?>;
                                color:<?= $quotation['status']=='Approved'?'#065f46':($quotation['status']=='Rejected'?'#991b1b':'#374151') ?>;">
                                <?= $quotation['status'] ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== ITEMS TABLE ===== -->
    <div class="qt-items-block mb-3">
        <table class="table qt-table mb-0">
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
    <div class="qt-totals-block mb-3" style="display:flex;justify-content:flex-end;">
        <table class="qt-totals-tbl" style="width:300px;">
            <tr>
                <td class="qt-dk fw-semibold">Subtotal</td>
                <td class="text-end conv-amount" data-amount="<?= $quotation['subtotal'] ?>"><?= formatMoney($quotation['subtotal']) ?></td>
            </tr>
            <?php if (floatval($quotation['tax_percentage']) > 0): ?>
            <tr id="qt-tax-row">
                <td class="qt-dk fw-semibold">VAT / Tax (<?= number_format($quotation['tax_percentage'], 0) ?>%)</td>
                <td class="text-end conv-amount" data-amount="<?= $quotation['vat_total'] ?>"><?= formatMoney($quotation['vat_total']) ?></td>
            </tr>
            <?php endif; ?>
            <tr style="border-top:2px solid #3a3f51;">
                <td class="fw-bold pt-2" style="color:#e8602c;font-size:1rem;">AMOUNT DUE</td>
                <td class="text-end fw-bold pt-2 conv-amount" style="color:#e8602c;font-size:1rem;" data-amount="<?= $quotation['grand_total'] ?>"><?= formatMoney($quotation['grand_total']) ?></td>
            </tr>
        </table>
    </div>

    <!-- ===== BANKING DETAILS (UAE) ===== -->
    <?php if (!empty($bankDetails)): ?>
    <div class="qt-bank-box mb-3" id="uae-bank-block">
        <h6 class="fw-bold mb-2">BANKING DETAILS FOR PAYMENT</h6>
        <div class="text-muted" style="font-size:.85rem; white-space: pre-line;"><?= htmlspecialchars($bankDetails) ?></div>
    </div>
    <?php endif; ?>

    <!-- ===== BANKING DETAILS (INDIA — shown only on INR export) ===== -->
    <div class="qt-india-box mb-3" id="india-bank-block" style="display:none;">
        <h6 class="fw-bold mb-2" style="color:#e8602c;">BANKING DETAILS FOR PAYMENT (India)</h6>
        <table style="font-size:.85rem; border-collapse:collapse; width:100%;">
            <tr><td style="color:#888; padding:2px 0; width:160px;">A/c No</td><td style="padding:2px 0; font-weight:600;">50200056346554</td></tr>
            <tr><td style="color:#888; padding:2px 0;">A/c Name</td><td style="padding:2px 0; font-weight:600;">SMARTFLIX TECHNOLOGIES LLP</td></tr>
            <tr><td style="color:#888; padding:2px 0;">Bank Name</td><td style="padding:2px 0; font-weight:600;">HDFC Bank Ltd, Hampankatta Mangalore Branch</td></tr>
            <tr><td style="color:#888; padding:2px 0;">IFSC Code</td><td style="padding:2px 0; font-weight:600;">HDFC0001571</td></tr>
        </table>
    </div>

    <!-- ===== TERMS & CONDITIONS ===== -->
    <?php if (!empty($quotation['terms_conditions'])): ?>
    <div class="qt-terms-box mb-4">
        <h6 class="fw-bold text-uppercase small mb-2">Terms & Conditions</h6>
        <div class="text-muted small" style="white-space: pre-line;"><?= htmlspecialchars($quotation['terms_conditions']) ?></div>
    </div>
    <?php endif; ?>

    <!-- ===== SIGNATURES ===== -->
    <div class="qt-sig-block" style="display:table;width:100%;margin-top:2rem;margin-bottom:1rem;">
        <div style="display:table-cell;width:50%;text-align:center;padding:0 1rem;">
            <div class="qt-sig-line" style="display:inline-block;">
                Authorized Signature<br>
                <small style="color:#6b7280;"><?= htmlspecialchars($companyName) ?></small>
            </div>
        </div>
        <div style="display:table-cell;width:50%;text-align:center;padding:0 1rem;">
            <div class="qt-sig-line" style="display:inline-block;">
                Customer Acceptance<br>
                <small style="color:#6b7280;"><?= htmlspecialchars($lead['lead_name'] ?: ($lead['company_name'] ?? '')) ?></small>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER BAR ===== -->
    <div class="qt-footer-bar mt-4">
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
const origSymbol = <?= json_encode($settings['currency_symbol'] ?? 'AED') ?>;
let converted = false;

function fmtConverted(amount, symbol, decimals) {
    return symbol + Number(amount).toLocaleString('en-IN', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const currSel  = document.getElementById('convertCurrency');
    const rateSufx = document.getElementById('rate-suffix');

    currSel.addEventListener('change', function () {
        rateSufx.textContent = this.options[this.selectedIndex].dataset.label;
    });

    document.getElementById('btnApplyConvert').addEventListener('click', function () {
        const opt    = currSel.options[currSel.selectedIndex];
        const symbol = opt.dataset.symbol;
        const label  = opt.dataset.label;
        const rate   = parseFloat(document.getElementById('exchangeRate').value) || 1;

        // Convert all amount cells
        document.querySelectorAll('.conv-amount').forEach(el => {
            const orig = parseFloat(el.dataset.amount) || 0;
            el.textContent = fmtConverted(orig * rate, symbol, 2);
        });

        // Update currency label in header
        const lbl = document.getElementById('qt-currency-label');
        if (lbl) lbl.textContent = label + '  (1 ' + origSymbol + ' = ' + rate + ' ' + label + ')';

        // Show / hide bank blocks
        const indiaBk = document.getElementById('india-bank-block');
        const uaeBk   = document.getElementById('uae-bank-block');
        if (label === 'INR') {
            if (indiaBk) indiaBk.style.display = '';
            if (uaeBk)   uaeBk.style.display   = 'none';
        } else {
            if (indiaBk) indiaBk.style.display = 'none';
            if (uaeBk)   uaeBk.style.display   = '';
        }

        converted = true;
        bootstrap.Modal.getInstance(document.getElementById('currencyModal')).hide();
        setTimeout(() => printClean(), 350);
    });

    document.getElementById('btnResetCurrency').addEventListener('click', function () {
        if (converted) location.reload();
    });
});

function printClean() {
    const prev = document.title;
    document.title = '';
    window.addEventListener('afterprint', () => { document.title = prev; }, { once: true });
    window.print();
}
</script>
