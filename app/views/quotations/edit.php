<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Edit Quotation</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>quotations/update/<?= $quotation['id'] ?>" method="POST">
                    
                    <!-- Top Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Client / Lead</label>
                            <select name="lead_id" class="form-select bg-light border-0" required>
                                <option value="">Select a Client...</option>
                                <?php foreach ($leads as $lead): ?>
                                    <option value="<?= $lead['id'] ?>" <?= ($lead['id'] == $quotation['lead_id']) ? 'selected' : '' ?>>
                                        <?= $lead['lead_name'] ?> (<?= $lead['company_name'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Quotation No</label>
                            <input type="text" name="quotation_no" class="form-control fw-bold border-0 bg-light" value="<?= $quotation['quotation_no'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                             <label class="form-label text-muted">Valid Until</label>
                             <input type="date" name="valid_until" class="form-control border-0 bg-light" value="<?= $quotation['valid_until'] ?>" required>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Quotation Items</h6>
                    <div id="items-container">
                        <?php foreach($items as $item): ?>
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-md-4">
                                <label class="small text-muted">Service / Item</label>
                                <input class="form-control" list="revenueTypes" name="item_name[]" value="<?= htmlspecialchars($item['item_name']) ?>" required>
                                <datalist id="revenueTypes">
                                    <option value="SmartApp Registration">
                                    <option value="SmartApp Ads">
                                    <option value="App Development">
                                    <option value="Website Design & Development">
                                    <option value="Social Media Marketing">
                                    <option value="Video Production">
                                    <option value="SEO Services">
                                    <option value="Consulting">
                                </datalist>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted">Description</label>
                                <input type="text" name="description[]" class="form-control" value="<?= htmlspecialchars($item['description']) ?>">
                            </div>
                            <div class="col-md-1">
                                <label class="small text-muted">Qty</label>
                                <input type="number" name="qty[]" class="form-control" value="<?= $item['qty'] ?>" min="1" onchange="calcTotal()">
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted">Price</label>
                                <input type="number" name="unit_price[]" class="form-control" value="<?= $item['unit_price'] ?>" min="0" step="0.01" onchange="calcTotal()">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <?php endforeach; ?>

                         <?php if(empty($items)): ?>
                         <div class="row g-2 mb-2 item-row">
                             <div class="col-md-4">
                                <input class="form-control" list="revenueTypes" name="item_name[]" placeholder="Select or type..." required>
                             </div>
                             <div class="col-md-4">
                                <input type="text" name="description[]" class="form-control" placeholder="Details...">
                             </div>
                             <div class="col-md-1">
                                <input type="number" name="qty[]" class="form-control" value="1" min="1" onchange="calcTotal()">
                             </div>
                             <div class="col-md-2">
                                <input type="number" name="unit_price[]" class="form-control" value="0" min="0" step="0.01" onchange="calcTotal()">
                             </div>
                             <div class="col-md-1 d-flex align-items-end">
                                 <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                             </div>
                         </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-light btn-sm mt-2" id="add-row"><i class="fas fa-plus"></i> Add Item</button>

                    <!-- Totals -->
                    <?php $taxOn = floatval($quotation['tax_percentage']) > 0; ?>
                    <div class="row mt-4 align-items-end justify-content-between">
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="apply_tax" id="applyTax" <?= $taxOn ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="applyTax">
                                    Apply Tax / VAT <span class="text-muted fw-normal">(<?= $tax_percentage ?>%)</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm table-borderless text-end mb-0">
                                <tr>
                                    <td class="text-muted">Subtotal:</td>
                                    <td class="fw-bold" id="disp-subtotal"><?= formatMoney($quotation['subtotal']) ?></td>
                                </tr>
                                <tr id="tax-row" <?= !$taxOn ? 'style="display:none"' : '' ?>>
                                    <td class="text-muted">Tax (<?= $tax_percentage ?>%):</td>
                                    <td class="text-muted" id="disp-vat"><?= formatMoney($quotation['vat_total']) ?></td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fs-5 fw-bold">Total:</td>
                                    <td class="fs-5 fw-bold text-primary" id="disp-grand"><?= formatMoney($quotation['grand_total']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="Draft" <?= ($quotation['status'] == 'Draft') ? 'selected' : '' ?>>Draft</option>
                                <option value="Sent" <?= ($quotation['status'] == 'Sent') ? 'selected' : '' ?>>Sent</option>
                                <option value="Approved" <?= ($quotation['status'] == 'Approved') ? 'selected' : '' ?>>Approved</option>
                                <option value="Rejected" <?= ($quotation['status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                            <label class="form-label fw-bold mb-0">Terms &amp; Conditions</label>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="text-muted small me-1" style="line-height:2">Templates:</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary tc-template" data-tpl="standard">Standard</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary tc-template" data-tpl="fabric">Fabric / Textile</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary tc-template" data-tpl="simple">Simple</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary tc-template" data-tpl="export">Export</button>
                                <button type="button" class="btn btn-sm btn-outline-danger tc-template" data-tpl="clear">Clear</button>
                            </div>
                        </div>
                        <textarea name="terms_conditions" id="terms_conditions" class="form-control bg-light border-0" rows="7"><?= htmlspecialchars($quotation['terms_conditions'] ?? '') ?></textarea>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= BASE_URL ?>quotations" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Update Quotation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-row');
    const currencySettings = <?= currencySettingsJson() ?>;

    // Helper to format currency (mirrors PHP formatMoney)
    const formatMoney = (amount) => {
        let thousSep = currencySettings.thousands_separator;
        if (thousSep === 'space') thousSep = ' ';
        if (thousSep === 'none') thousSep = '';
        const decPlaces = parseInt(currencySettings.decimal_places);
        const decSep = currencySettings.decimal_separator;

        const parts = amount.toFixed(decPlaces).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousSep);
        const formatted = parts.length > 1 ? parts[0] + decSep + parts[1] : parts[0];

        return currencySettings.position === 'after'
            ? formatted + ' ' + currencySettings.symbol
            : currencySettings.symbol + formatted;
    };

    addBtn.addEventListener('click', function() {
        // Clone last row or create if none
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 0) {
            const row = rows[0].cloneNode(true);
            row.querySelectorAll('input').forEach(i => i.value = '');
            row.querySelector('input[name="qty[]"]').value = 1;
            row.querySelector('input[name="unit_price[]"]').value = 0;
            container.appendChild(row);
        }
        bindEvents();
    });

    function bindEvents() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.onclick = function() {
                if(document.querySelectorAll('.item-row').length > 1) {
                    this.closest('.item-row').remove();
                    calcTotal();
                }
            };
        });
        document.querySelectorAll('input').forEach(input => {
            input.oninput = calcTotal;
        });
    }

    const taxToggle = document.getElementById('applyTax');
    const taxRate   = <?= floatval($tax_percentage) ?> / 100;

    taxToggle.addEventListener('change', function() {
        document.getElementById('tax-row').style.display = this.checked ? '' : 'none';
        calcTotal();
    });

    window.calcTotal = function() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty   = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
            subtotal += qty * price;
        });

        const applyTax = document.getElementById('applyTax').checked;
        const vat   = applyTax ? subtotal * taxRate : 0;
        const grand = subtotal + vat;

        document.getElementById('disp-subtotal').innerText = formatMoney(subtotal);
        document.getElementById('disp-vat').innerText      = formatMoney(vat);
        document.getElementById('disp-grand').innerText    = formatMoney(grand);
    }

    bindEvents();

    // ── Terms & Conditions Templates ──────────────────────────────────
    const tcTemplates = {
        standard: `1. This quotation is valid for 30 days from the date of issue.\n2. Payment Terms: 50% advance upon order confirmation, balance before delivery.\n3. Prices are subject to change after the validity period without prior notice.\n4. Delivery schedule will be confirmed upon receipt of advance payment.\n5. Goods once delivered will not be accepted back without prior written approval.\n6. Any dispute shall be subject to local jurisdiction and applicable laws.`,

        fabric: `1. This quotation is valid for 30 days from the date of issue.\n2. Fabric shades may slightly vary from samples due to dye lot differences; this is not considered a defect.\n3. Minimum Order Quantity (MOQ) applies as specified per item.\n4. Payment Terms: 50% advance upon order confirmation, balance before dispatch.\n5. Lead time: 7–14 working days after order confirmation and receipt of advance payment.\n6. Damaged or defective goods must be reported within 48 hours of receipt with photographic evidence.\n7. We are not responsible for delays caused by force majeure events (floods, strikes, port closures, etc.).\n8. All prices exclude VAT unless stated otherwise.`,

        simple: `1. Quotation valid for 30 days from date of issue.\n2. 50% advance payment required to confirm the order.\n3. Balance payment due before delivery.\n4. Prices are exclusive of VAT unless stated otherwise.\n5. Delivery timeline to be agreed upon order confirmation.`,

        export: `1. This quotation is valid for 30 days from the date of issue.\n2. Prices are quoted EXW (Ex-Works) unless otherwise stated.\n3. Payment Terms: 100% advance via bank transfer prior to production/dispatch.\n4. Shipping, freight, and insurance charges are to the buyer's account unless stated otherwise.\n5. Lead time: 14–21 working days after order confirmation and payment clearance.\n6. Goods are inspected prior to dispatch. Claims must be raised within 5 days of receipt.\n7. All disputes are subject to UAE jurisdiction and applicable laws.\n8. Force majeure events absolve the seller of delay-related liability.`,

        clear: ''
    };

    document.querySelectorAll('.tc-template').forEach(btn => {
        btn.addEventListener('click', function () {
            const tpl = this.dataset.tpl;
            const ta  = document.getElementById('terms_conditions');
            if (tpl === 'clear') {
                ta.value = '';
            } else {
                ta.value = tcTemplates[tpl];
            }
            ta.focus();
        });
    });
    // ─────────────────────────────────────────────────────────────────
});
</script>
