<style>
.inv-row td { font-size: .875rem; vertical-align: middle; }
.inv-row input[type=checkbox] { width: 1.1rem; height: 1.1rem; cursor: pointer; }
.section-card { border: 1px solid #e9ecef; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; background: #fff; }
.section-title { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #6c757d; margin-bottom: 1rem; }
#invoiceTable thead { background: #3a3f51; color: #fff; font-size: .72rem; text-transform: uppercase; letter-spacing: .4px; }
</style>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= BASE_URL ?>paymentvoucher" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i></a>
    <span class="text-muted">Payment Vouchers</span>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">Failed to save voucher. Please try again.</div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>paymentvoucher/store" id="pvForm">

    <!-- Row 1: Voucher meta -->
    <div class="section-card">
        <p class="section-title">Voucher Details</p>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Voucher No</label>
                <input type="text" name="voucher_no" class="form-control" value="<?= htmlspecialchars($voucher_no) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Payment Date <span class="text-danger">*</span></label>
                <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Payment Mode <span class="text-danger">*</span></label>
                <select name="payment_mode" class="form-select" required>
                    <option value="Cash">Cash</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Online">Online</option>
                    <option value="Credit Card">Credit Card</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Reference Number</label>
                <input type="text" name="reference_number" class="form-control" placeholder="Cheque / TRX no.">
            </div>
        </div>
    </div>

    <!-- Row 2: Company selector -->
    <div class="section-card">
        <p class="section-title">Select Company</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Company / Client <span class="text-danger">*</span></label>
                <select name="lead_id" id="leadSelect" class="form-select" required>
                    <option value="">— Select company —</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>">
                        <?= htmlspecialchars($c['company_name'] ?: $c['lead_name']) ?>
                        <?php if ($c['company_name'] && $c['lead_name']): ?>
                            (<?= htmlspecialchars($c['lead_name']) ?>)
                        <?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="button" id="loadInvBtn" class="btn btn-outline-secondary" onclick="loadInvoices()" disabled>
                    <i class="fas fa-sync-alt me-1"></i>Load Invoices / Statements
                </button>
            </div>
        </div>
    </div>

    <!-- Row 3: Payment statements (invoices) -->
    <div class="section-card" id="invoiceSection" style="display:none;">
        <p class="section-title">Payment Statements <span class="text-muted fw-normal" style="font-size:.7rem;">(Select invoices to include in this voucher)</span></p>

        <div id="invoiceLoader" class="text-center py-3 d-none">
            <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
            <span class="ms-2 text-muted small">Loading...</span>
        </div>

        <div class="table-responsive mb-3" id="invoiceTableWrap" style="display:none;">
            <table class="table table-bordered mb-0" id="invoiceTable">
                <thead>
                    <tr>
                        <th style="width:44px;" class="text-center py-2">
                            <input type="checkbox" id="selectAll" title="Select all">
                        </th>
                        <th class="py-2">Invoice No</th>
                        <th class="py-2">Date</th>
                        <th class="py-2">Due Date</th>
                        <th class="py-2 text-end">Total</th>
                        <th class="py-2 text-end">Paid</th>
                        <th class="py-2 text-end">Balance</th>
                        <th class="py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="invoiceTbody"></tbody>
            </table>
        </div>

        <div id="noInvoicesMsg" class="text-muted small text-center py-2 d-none">
            No invoices found for this company.
        </div>

        <!-- Selected items summary (hidden inputs built dynamically) -->
        <div id="itemInputs"></div>
    </div>

    <!-- Row 4: Description & total -->
    <div class="section-card">
        <p class="section-title">Payment Summary</p>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Description / Purpose</label>
                <input type="text" name="description" class="form-control" placeholder="e.g. Payment for invoices INV-2025-0001 to 0003">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Total Amount (<?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?>) <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="totalAmount" class="form-control fw-bold fs-5" step="0.01" min="0" placeholder="0.00" required>
                <div class="form-text" id="autoCalcHint" style="display:none;">Auto-calculated from selected invoices.</div>
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-12">
                <label class="form-label fw-semibold small">Internal Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Optional internal notes..."></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
        <a href="<?= BASE_URL ?>paymentvoucher" class="btn btn-light px-4">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Save &amp; View Voucher
        </button>
    </div>

</form>

<script>
const BASE_URL = '<?= BASE_URL ?>';
const leadSelect = document.getElementById('leadSelect');
const loadBtn    = document.getElementById('loadInvBtn');

leadSelect.addEventListener('change', function() {
    loadBtn.disabled = !this.value;
    document.getElementById('invoiceSection').style.display = 'none';
});

function loadInvoices() {
    const leadId = leadSelect.value;
    if (!leadId) return;

    document.getElementById('invoiceSection').style.display = 'block';
    document.getElementById('invoiceLoader').classList.remove('d-none');
    document.getElementById('invoiceTableWrap').style.display = 'none';
    document.getElementById('noInvoicesMsg').classList.add('d-none');

    fetch(BASE_URL + 'paymentvoucher/invoices?lead_id=' + leadId)
        .then(r => r.json())
        .then(data => {
            document.getElementById('invoiceLoader').classList.add('d-none');
            const tbody = document.getElementById('invoiceTbody');
            tbody.innerHTML = '';
            document.getElementById('itemInputs').innerHTML = '';

            if (!data.length) {
                document.getElementById('noInvoicesMsg').classList.remove('d-none');
                return;
            }

            document.getElementById('invoiceTableWrap').style.display = 'block';
            data.forEach((inv, idx) => {
                const balance = parseFloat(inv.balance_due);
                const statusBadge = inv.status === 'Paid'
                    ? '<span class="badge bg-success">Paid</span>'
                    : inv.status === 'Partial'
                    ? '<span class="badge bg-warning text-dark">Partial</span>'
                    : '<span class="badge bg-danger">Unpaid</span>';

                const row = `<tr class="inv-row" data-amount="${balance}" data-inv="${inv.invoice_no}">
                    <td class="text-center">
                        <input type="checkbox" class="inv-check"
                               value="${balance}" data-inv="${inv.invoice_no}" data-desc="Payment for ${inv.invoice_no}">
                    </td>
                    <td class="fw-semibold text-primary">${inv.invoice_no}</td>
                    <td class="text-muted">${formatDate(inv.created_at)}</td>
                    <td class="text-muted">${inv.due_date ? formatDate(inv.due_date) : '—'}</td>
                    <td class="text-end">${formatNum(inv.grand_total)}</td>
                    <td class="text-end text-success">${formatNum(inv.amount_received)}</td>
                    <td class="text-end ${balance > 0 ? 'text-danger' : 'text-success'} fw-semibold">${formatNum(balance)}</td>
                    <td class="text-center">${statusBadge}</td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            // Checkbox events
            document.querySelectorAll('.inv-check').forEach(cb => {
                cb.addEventListener('change', recalculate);
            });
            document.getElementById('selectAll').addEventListener('change', function() {
                document.querySelectorAll('.inv-check').forEach(cb => {
                    cb.checked = this.checked;
                });
                recalculate();
            });
        })
        .catch(() => {
            document.getElementById('invoiceLoader').classList.add('d-none');
            document.getElementById('noInvoicesMsg').classList.remove('d-none');
            document.getElementById('noInvoicesMsg').textContent = 'Error loading invoices.';
        });
}

function recalculate() {
    let total = 0;
    const inputs = document.getElementById('itemInputs');
    inputs.innerHTML = '';

    let idx = 0;
    document.querySelectorAll('.inv-check:checked').forEach(cb => {
        const amt = parseFloat(cb.value) || 0;
        total += amt;
        inputs.innerHTML += `
            <input type="hidden" name="item_desc[]"   value="${escHtml(cb.dataset.desc)}">
            <input type="hidden" name="item_inv_no[]" value="${escHtml(cb.dataset.inv)}">
            <input type="hidden" name="item_amount[]" value="${amt.toFixed(2)}">`;
        idx++;
    });

    const amtField = document.getElementById('totalAmount');
    if (idx > 0) {
        amtField.value = total.toFixed(2);
        document.getElementById('autoCalcHint').style.display = 'block';
    } else {
        amtField.value = '';
        document.getElementById('autoCalcHint').style.display = 'none';
    }
}

function formatDate(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatNum(n) {
    return parseFloat(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>
