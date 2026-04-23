<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h4 class="fw-bold mt-2"><i class="fas fa-cogs"></i> System Settings</h4>
            </div>
            <div class="card-body">
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Settings updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>settings/update" method="POST">
                    
                    <h5 class="border-bottom pb-2 mb-3 text-primary">Financial Settings</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="<?= $settings['currency_symbol'] ?? 'AED' ?>" required>
                            <div class="form-text">e.g. $, AED, EUR, £</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">VAT / Tax Percentage (%)</label>
                            <div class="input-group">
                                <input type="number" name="tax_percentage" class="form-control" step="0.01" min="0" value="<?= $settings['tax_percentage'] ?? '5' ?>" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Default tax rate applied to new invoices.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Symbol Position</label>
                            <select name="currency_position" class="form-select">
                                <option value="before" <?= ($settings['currency_position'] ?? 'before') === 'before' ? 'selected' : '' ?>>Before amount ($100)</option>
                                <option value="after" <?= ($settings['currency_position'] ?? 'before') === 'after' ? 'selected' : '' ?>>After amount (100 AED)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Decimal Separator</label>
                            <select name="decimal_separator" class="form-select">
                                <option value="." <?= ($settings['decimal_separator'] ?? '.') === '.' ? 'selected' : '' ?>>Dot (1234.56)</option>
                                <option value="," <?= ($settings['decimal_separator'] ?? '.') === ',' ? 'selected' : '' ?>>Comma (1234,56)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Thousands Separator</label>
                            <select name="thousands_separator" class="form-select">
                                <option value="," <?= ($settings['thousands_separator'] ?? ',') === ',' ? 'selected' : '' ?>>Comma (1,234)</option>
                                <option value="." <?= ($settings['thousands_separator'] ?? ',') === '.' ? 'selected' : '' ?>>Dot (1.234)</option>
                                <option value="space" <?= ($settings['thousands_separator'] ?? ',') === 'space' ? 'selected' : '' ?>>Space (1 234)</option>
                                <option value="none" <?= ($settings['thousands_separator'] ?? ',') === 'none' ? 'selected' : '' ?>>None (1234)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Decimal Places</label>
                            <select name="decimal_places" class="form-select">
                                <option value="0" <?= ($settings['decimal_places'] ?? '2') === '0' ? 'selected' : '' ?>>0 (1,234)</option>
                                <option value="2" <?= ($settings['decimal_places'] ?? '2') === '2' ? 'selected' : '' ?>>2 (1,234.56)</option>
                                <option value="3" <?= ($settings['decimal_places'] ?? '2') === '3' ? 'selected' : '' ?>>3 (1,234.567)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-text"><strong>Preview:</strong> <span id="currency-preview" class="fw-bold text-primary fs-5"></span></div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 mt-4 text-primary">Company Information (For Invoices)</h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="<?= $settings['company_name'] ?? '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="company_address" class="form-control" rows="3"><?= $settings['company_address'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" name="company_phone" class="form-control" value="<?= $settings['company_phone'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="company_email" class="form-control" value="<?= $settings['company_email'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Website</label>
                            <input type="text" name="company_website" class="form-control" value="<?= $settings['company_website'] ?? '' ?>" placeholder="www.example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">TRN (Tax Registration Number)</label>
                            <input type="text" name="company_trn" class="form-control" value="<?= $settings['company_trn'] ?? '' ?>" placeholder="e.g. 104279403000001">
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 mt-4 text-primary">Invoice / Quotation Settings</h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Banking Details for Payment</label>
                        <textarea name="bank_details" class="form-control" rows="4" placeholder="IBAN, Branch, Swift Code, etc."><?= $settings['bank_details'] ?? '' ?></textarea>
                        <div class="form-text">Displayed on invoices for payment reference.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Default Terms & Conditions</label>
                        <textarea name="invoice_terms" class="form-control" rows="4" placeholder="Enter default terms & conditions..."><?= $settings['invoice_terms'] ?? '' ?></textarea>
                        <div class="form-text">Shown at the bottom of invoices. Can be overridden per invoice.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Invoice Footer Note</label>
                        <input type="text" name="invoice_footer" class="form-control" value="<?= $settings['invoice_footer'] ?? '' ?>" placeholder="e.g. Thank you for your business!">
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save"></i> Save Settings</button>
                    </div>
                </form>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const fields = ['currency_symbol','currency_position','decimal_separator','thousands_separator','decimal_places'];
                    function updatePreview() {
                        const symbol = document.querySelector('[name="currency_symbol"]').value || '$';
                        const position = document.querySelector('[name="currency_position"]').value;
                        const decSep = document.querySelector('[name="decimal_separator"]').value;
                        const thousSep = document.querySelector('[name="thousands_separator"]').value;
                        const decPlaces = parseInt(document.querySelector('[name="decimal_places"]').value);

                        let sep = thousSep;
                        if (sep === 'space') sep = ' ';
                        if (sep === 'none') sep = '';

                        const num = 1234567.89;
                        const parts = num.toFixed(decPlaces).split('.');
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
                        const formatted = parts.length > 1 ? parts[0] + decSep + parts[1] : parts[0];

                        const preview = document.getElementById('currency-preview');
                        preview.textContent = position === 'after' ? formatted + ' ' + symbol : symbol + formatted;
                    }
                    fields.forEach(function(name) {
                        const el = document.querySelector('[name="' + name + '"]');
                        if (el) { el.addEventListener('input', updatePreview); el.addEventListener('change', updatePreview); }
                    });
                    updatePreview();
                });
                </script>
            </div>
        </div>
    </div>
</div>
