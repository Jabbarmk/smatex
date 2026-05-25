<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Generate New Invoice</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>invoices/store" method="POST">
                    
                    <!-- Load from Quotation -->
                    <?php if (!empty($quotations)): ?>
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Load from Quotation <span class="text-info">(optional)</span></label>
                            <select id="quotation-select" class="form-select bg-light border-0">
                                <option value="">-- Select a Quotation --</option>
                                <?php foreach ($quotations as $q): ?>
                                    <option value="<?= $q['id'] ?>"><?= htmlspecialchars($q['quotation_no']) ?> &mdash; <?= htmlspecialchars($q['lead_name'] . ' (' . $q['company_name'] . ')') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <span class="text-muted small" id="quotation-load-msg"></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Top Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Client / Lead</label>
                            <select name="lead_id" id="lead-select" class="form-select bg-light border-0" required>
                                <option value="">Select a Client...</option>
                                <?php foreach ($leads as $lead): ?>
                                    <option value="<?= $lead['id'] ?>"><?= $lead['lead_name'] ?> (<?= $lead['company_name'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Invoice No</label>
                            <input type="text" name="invoice_no" class="form-control fw-bold border-0 bg-light" value="<?= $invoice_no ?>" readonly>
                        </div>
                        <div class="col-md-4">
                             <label class="form-label text-muted">Due Date</label>
                             <input type="date" name="due_date" class="form-control border-0 bg-light" required>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Revenue Items</h6>
                    <div id="items-container">
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-md-4">
                                <label class="small text-muted">Service / Item</label>
                                <input class="form-control" list="revenueTypes" name="item_name[]" placeholder="Select or type..." required>
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
                                <textarea name="description[]" class="form-control" rows="3" placeholder="Details..."></textarea>
                            </div>
                            <div class="col-md-1">
                                <label class="small text-muted">Qty</label>
                                <input type="number" name="qty[]" class="form-control" value="1" min="1" onchange="calcTotal()">
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted">Price</label>
                                <input type="number" name="unit_price[]" class="form-control" value="0" min="0" step="0.01" onchange="calcTotal()">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-light btn-sm mt-2" id="add-row"><i class="fas fa-plus"></i> Add Item</button>

                    <!-- Totals -->
                    <div class="row mt-4 justify-content-end">
                        <div class="col-md-5">
                            <table class="table table-sm table-borderless text-end">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="fw-bold" id="disp-subtotal"><?= formatMoney(0) ?></td>
                                </tr>
                                <tr>
                                    <td>Discount:</td>
                                    <td>
                                        <div class="input-group input-group-sm justify-content-end">
                                            <input type="number" name="discount" id="discount-input" class="form-control form-control-sm text-end" style="max-width: 100px;" value="0" min="0" step="0.01">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline float-end me-0">
                                            <input class="form-check-input" type="checkbox" name="tax_enabled" id="tax-check" checked>
                                            <label class="form-check-label" for="tax-check">Tax (<?= $tax_percentage ?>%):</label>
                                            <input type="hidden" name="tax_percentage" value="<?= $tax_percentage ?>">
                                        </div>
                                    </td>
                                    <td class="text-muted" id="disp-vat"><?= formatMoney(0) ?></td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fs-5 fw-bold">Total:</td>
                                    <td class="fs-5 fw-bold text-primary" id="disp-grand"><?= formatMoney(0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-4">
                        <label class="form-label">Payment Terms / Notes</label>
                        <textarea name="payment_terms" class="form-control bg-light border-0" rows="2"></textarea>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= BASE_URL ?>invoices" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-paper-plane"></i> Create Invoice</button>
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
    
    // Currency format settings from PHP
    const currencySettings = <?= currencySettingsJson() ?>;
    const taxRate = <?= floatval($tax_percentage) ?> / 100;

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

    // Main Calculation Function
    const calcTotal = () => {
        let subtotal = 0;
        
        // Sum up all rows
        document.querySelectorAll('.item-row').forEach(row => {
            const qtyInput = row.querySelector('input[name="qty[]"]');
            const priceInput = row.querySelector('input[name="unit_price[]"]');
            
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            
            subtotal += (qty * price);
        });

        // Get Discount
        const discountInput = document.getElementById('discount-input');
        const discount = discountInput ? (parseFloat(discountInput.value) || 0) : 0;
        
        // Calculate Taxable
        let taxable = subtotal - discount;
        if(taxable < 0) taxable = 0;

        // Calculate VAT
        let vat = 0;
        const taxCheck = document.getElementById('tax-check');
        if(taxCheck && taxCheck.checked) {
            vat = taxable * taxRate;
        }

        const grand = taxable + vat;

        // Update Display
        const dispSub = document.getElementById('disp-subtotal');
        const dispVat = document.getElementById('disp-vat');
        const dispGrand = document.getElementById('disp-grand');

        if(dispSub) dispSub.innerText = formatMoney(subtotal);
        if(dispVat) dispVat.innerText = formatMoney(vat);
        if(dispGrand) dispGrand.innerText = formatMoney(grand);
    };

    // Attach events to a row's inputs
    const attachRowEvents = (row) => {
        row.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', calcTotal);
            input.addEventListener('change', calcTotal); // Handle spinners/etc
        });
        
        const removeBtn = row.querySelector('.remove-row');
        if(removeBtn) {
            removeBtn.onclick = function() {
                if(document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    calcTotal();
                } else {
                    alert("You must have at least one item.");
                }
            };
        }
    };

    // "Add Item" Handler
    addBtn.addEventListener('click', function() {
        const rows = document.querySelectorAll('.item-row');
        if(rows.length > 0) {
            const clone = rows[0].cloneNode(true);
            clone.querySelectorAll('input').forEach(i => i.value = '');
            
            // Set defaults
            const qty = clone.querySelector('input[name="qty[]"]');
            const price = clone.querySelector('input[name="unit_price[]"]');
            if(qty) qty.value = 1;
            if(price) price.value = 0;
            
            container.appendChild(clone);
            attachRowEvents(clone);
        }
    });

    // Global Totals Events
    const discountEl = document.getElementById('discount-input');
    if(discountEl) {
        discountEl.addEventListener('input', calcTotal);
        discountEl.addEventListener('change', calcTotal);
    }

    const taxCheckEl = document.getElementById('tax-check');
    if(taxCheckEl) {
        taxCheckEl.addEventListener('change', calcTotal);
    }

    // Initialize existing rows
    document.querySelectorAll('.item-row').forEach(row => {
        attachRowEvents(row);
    });
    
    // Global calc expose for inline handlers (fallback)
    window.calcTotal = calcTotal;

    // Run once
    calcTotal();

    // ── Load from Quotation ──
    const quotationSelect = document.getElementById('quotation-select');
    if (quotationSelect) {
        quotationSelect.addEventListener('change', function () {
            const qid = this.value;
            if (!qid) return;

            const msg = document.getElementById('quotation-load-msg');
            if (msg) msg.textContent = 'Loading...';

            fetch('<?= BASE_URL ?>invoices/getQuotation/' + qid)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        if (msg) msg.textContent = 'Error: ' + data.error;
                        return;
                    }

                    const q = data.quotation;
                    const items = data.items;

                    // Set lead
                    const leadSel = document.getElementById('lead-select');
                    if (leadSel && q.lead_id) {
                        leadSel.value = q.lead_id;
                    }

                    // Set tax
                    const taxCheck = document.getElementById('tax-check');
                    if (taxCheck) {
                        taxCheck.checked = parseFloat(q.tax_percentage) > 0;
                    }

                    // Clear existing item rows and rebuild
                    container.innerHTML = '';

                    const buildRow = (item) => {
                        const firstRow = document.createElement('div');
                        firstRow.className = 'row g-2 mb-2 item-row';
                        firstRow.innerHTML = `
                            <div class="col-md-4">
                                <label class="small text-muted">Service / Item</label>
                                <input class="form-control" list="revenueTypes" name="item_name[]" placeholder="Select or type..." value="${escHtml(item.item_name || '')}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted">Description</label>
                                <textarea name="description[]" class="form-control" rows="3" placeholder="Details...">${escHtml(item.description || '')}</textarea>
                            </div>
                            <div class="col-md-1">
                                <label class="small text-muted">Qty</label>
                                <input type="number" name="qty[]" class="form-control" value="${parseFloat(item.qty) || 1}" min="1">
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted">Price</label>
                                <input type="number" name="unit_price[]" class="form-control" value="${parseFloat(item.unit_price) || 0}" min="0" step="0.01">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button>
                            </div>`;
                        return firstRow;
                    };

                    if (items && items.length > 0) {
                        items.forEach(item => {
                            const row = buildRow(item);
                            container.appendChild(row);
                            attachRowEvents(row);
                        });
                    } else {
                        // Add a blank row if quotation has no items
                        addBtn.click();
                    }

                    calcTotal();

                    if (msg) msg.textContent = 'Quotation data loaded successfully.';
                    setTimeout(() => { if (msg) msg.textContent = ''; }, 3000);
                })
                .catch(() => {
                    if (msg) msg.textContent = 'Failed to load quotation data.';
                });
        });
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }
});
</script>
