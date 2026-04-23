<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Edit Invoice</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>invoices/update/<?= $invoice['id'] ?>" method="POST">
                    
                    <!-- Top Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Client / Lead</label>
                            <select name="lead_id" class="form-select bg-light border-0" required>
                                <option value="">Select a Client...</option>
                                <?php foreach ($leads as $lead): ?>
                                    <option value="<?= $lead['id'] ?>" <?= ($lead['id'] == $invoice['lead_id']) ? 'selected' : '' ?>>
                                        <?= $lead['lead_name'] ?> (<?= $lead['company_name'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Invoice No</label>
                            <input type="text" name="invoice_no" class="form-control fw-bold border-0 bg-light" value="<?= $invoice['invoice_no'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                             <label class="form-label text-muted">Due Date</label>
                             <input type="date" name="due_date" class="form-control border-0 bg-light" value="<?= $invoice['due_date'] ?>" required>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Revenue Items</h6>
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
                                <textarea name="description[]" class="form-control" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
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
                             <!-- Copy from create view essentially, but JS adds rows anyway -->
                             <div class="col-md-4"><input class="form-control" list="revenueTypes" name="item_name[]" placeholder="Select or type..." required></div>
                             <!-- ... rest ... -->
                         </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-light btn-sm mt-2" id="add-row"><i class="fas fa-plus"></i> Add Item</button>

                    <!-- Totals -->
                    <!-- Totals -->
                    <div class="row mt-4 justify-content-end">
                        <div class="col-md-5">
                            <table class="table table-sm table-borderless text-end">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="fw-bold" id="disp-subtotal"><?= $currency_symbol ?><?= number_format($invoice['subtotal'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Discount:</td>
                                    <td>
                                        <div class="input-group input-group-sm justify-content-end">
                                            <input type="number" name="discount" id="discount-input" class="form-control form-control-sm text-end" style="max-width: 100px;" value="<?= $invoice['discount'] ?? 0 ?>" min="0" step="0.01">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline float-end me-0">
                                            <?php 
                                            // Determine if tax was enabled
                                            $inv_tax_pct = $invoice['tax_percentage'] ?? 0;
                                            $is_tax_enabled = ($inv_tax_pct > 0 || $invoice['vat_total'] > 0);
                                            // Use stored percentage if valid, else default
                                            $current_tax_pct = ($inv_tax_pct > 0) ? $inv_tax_pct : ($tax_percentage ?? 5); 
                                            ?>
                                            <input class="form-check-input" type="checkbox" name="tax_enabled" id="tax-check" <?= $is_tax_enabled ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="tax-check">Tax (<?= $current_tax_pct ?>%):</label>
                                            <input type="hidden" name="tax_percentage" value="<?= $current_tax_pct ?>">
                                        </div>
                                    </td>
                                    <td class="text-muted" id="disp-vat"><?= $currency_symbol ?><?= number_format($invoice['vat_total'], 2) ?></td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fs-5 fw-bold">Total:</td>
                                    <td class="fs-5 fw-bold text-primary" id="disp-grand"><?= $currency_symbol ?><?= number_format($invoice['grand_total'], 2) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label class="form-label">Payment Terms / Notes</label>
                            <textarea name="payment_terms" class="form-control bg-light border-0" rows="2"><?= htmlspecialchars($invoice['payment_terms']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="Unpaid" <?= ($invoice['status'] == 'Unpaid') ? 'selected' : '' ?>>Unpaid</option>
                                <option value="Partial" <?= ($invoice['status'] == 'Partial') ? 'selected' : '' ?>>Partial</option>
                                <option value="Paid" <?= ($invoice['status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= BASE_URL ?>invoices" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Update Invoice</button>
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
    const taxRate = <?= floatval($current_tax_pct) ?> / 100;

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
            input.addEventListener('change', calcTotal);
        });
        
        const removeBtn = row.querySelector('.remove-row');
        if(removeBtn) {
            removeBtn.onclick = function() {
                // For edit, ensure at least one row or just handle gracefully
                // Let's allow removing but check count
                if(document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    calcTotal();
                } else {
                    // If removing last row, maybe just clear values?
                     // Or alert.
                     alert("You must have at least one item. Add another before removing this one.");
                }
            };
        }
    };

    // "Add Item" Handler
    addBtn.addEventListener('click', function() {
        // Use existing structure or template
        // Better to use template string if last row removed or clean state needed
        // But for consistency let's try to clone if exists, else use template
        const rows = document.querySelectorAll('.item-row');
        let newRow;
        
        if(rows.length > 0) {
            newRow = rows[0].cloneNode(true);
            newRow.querySelectorAll('input').forEach(i => i.value = '');
            newRow.querySelectorAll('textarea').forEach(t => t.value = '');
            const qty = newRow.querySelector('input[name="qty[]"]');
            const price = newRow.querySelector('input[name="unit_price[]"]');
            if(qty) qty.value = 1;
            if(price) price.value = 0;
        } else {
             // Fallback template just in case
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = `
                <div class="row g-2 mb-2 item-row">
                    <div class="col-md-4"><input class="form-control" list="revenueTypes" name="item_name[]" placeholder="Select..." required></div>
                    <div class="col-md-4"><textarea name="description[]" class="form-control" rows="3" placeholder="Details..."></textarea></div>
                    <div class="col-md-1"><input type="number" name="qty[]" class="form-control" value="1" min="1"></div>
                    <div class="col-md-2"><input type="number" name="unit_price[]" class="form-control" value="0" min="0" step="0.01"></div>
                    <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fas fa-times"></i></button></div>
                </div>`;
            newRow = tempDiv.firstElementChild;
        }
        
        container.appendChild(newRow);
        attachRowEvents(newRow);
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
    
    // Global calc expose
    window.calcTotal = calcTotal;

    // Run once
    calcTotal();
});
</script>
