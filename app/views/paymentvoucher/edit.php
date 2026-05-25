<style>
.inv-row td { font-size: .875rem; vertical-align: middle; }
.inv-row input[type=checkbox] { width: 1.1rem; height: 1.1rem; cursor: pointer; }
.section-card { border: 1px solid #e9ecef; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; background: #fff; }
.section-title { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #6c757d; margin-bottom: 1rem; }
#invoiceTable thead { background: #3a3f51; color: #fff; font-size: .72rem; text-transform: uppercase; letter-spacing: .4px; }
.item-row td { vertical-align: middle; }
</style>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= BASE_URL ?>paymentvoucher/show/<?= $voucher['id'] ?>" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i></a>
    <span class="text-muted">Edit Voucher</span>
</div>

<form method="POST" action="<?= BASE_URL ?>paymentvoucher/update/<?= $voucher['id'] ?>" id="pvForm">

    <!-- Voucher Details -->
    <div class="section-card">
        <p class="section-title">Voucher Details</p>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Voucher No</label>
                <input type="text" name="voucher_no" class="form-control" value="<?= htmlspecialchars($voucher['voucher_no']) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Payment Date <span class="text-danger">*</span></label>
                <input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($voucher['payment_date']) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Payment Mode <span class="text-danger">*</span></label>
                <select name="payment_mode" class="form-select" required>
                    <?php foreach (['Cash','Cheque','Bank Transfer','Online','Credit Card'] as $m): ?>
                    <option value="<?= $m ?>" <?= $voucher['payment_mode'] === $m ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Reference Number</label>
                <input type="text" name="reference_number" class="form-control" value="<?= htmlspecialchars($voucher['reference_number'] ?? '') ?>" placeholder="Cheque / TRX no.">
            </div>
        </div>
    </div>

    <!-- Company -->
    <div class="section-card">
        <p class="section-title">Company / Client</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Company / Client <span class="text-danger">*</span></label>
                <select name="lead_id" id="leadSelect" class="form-select" required>
                    <option value="">— Select company —</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $voucher['lead_id'] == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['company_name'] ?: $c['lead_name']) ?>
                        <?php if ($c['company_name'] && $c['lead_name']): ?>
                            (<?= htmlspecialchars($c['lead_name']) ?>)
                        <?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="button" id="loadInvBtn" class="btn btn-outline-secondary" onclick="loadInvoices()">
                    <i class="fas fa-sync-alt me-1"></i>Load More Invoices
                </button>
            </div>
        </div>
    </div>

    <!-- Existing Line Items -->
    <div class="section-card">
        <p class="section-title">Payment Statements
            <span class="text-muted fw-normal" style="font-size:.7rem;">— edit or remove existing items, or load more below</span>
        </p>

        <div class="table-responsive mb-3">
            <table class="table table-bordered mb-0" id="itemsTable">
                <thead style="background:#3a3f51; color:#fff; font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                    <tr>
                        <th class="py-2">Invoice No</th>
                        <th class="py-2">Description</th>
                        <th class="py-2 text-end" style="width:160px;">Amount (<?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?>)</th>
                        <th class="py-2 text-center" style="width:56px;"></th>
                    </tr>
                </thead>
                <tbody id="itemsTbody">
                <?php foreach ($items as $item): ?>
                <tr class="item-row">
                    <td><input type="text" name="item_inv_no[]" class="form-control form-control-sm" value="<?= htmlspecialchars($item['invoice_no'] ?? '') ?>"></td>
                    <td><input type="text" name="item_desc[]" class="form-control form-control-sm" value="<?= htmlspecialchars($item['description']) ?>" required></td>
                    <td><input type="number" name="item_amount[]" class="form-control form-control-sm item-amount text-end" step="0.01" min="0" value="<?= htmlspecialchars($item['amount']) ?>" oninput="recalcTotal()"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr id="emptyRow">
                    <td colspan="4" class="text-center text-muted small py-3">No line items. Add via "Load More Invoices" below or add a row manually.</td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addManualRow()">
            <i class="fas fa-plus me-1"></i>Add Row Manually
        </button>
    </div>

    <!-- Load additional invoices from company -->
    <div class="section-card" id="invoiceSection" style="display:none;">
        <p class="section-title">Add from Invoice List</p>

        <div id="invoiceLoader" class="text-center py-3 d-none">
            <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
            <span class="ms-2 text-muted small">Loading...</span>
        </div>

        <div class="table-responsive mb-3" id="invoiceTableWrap" style="display:none;">
            <table class="table table-bordered mb-0" id="invoiceTable">
                <thead style="background:#3a3f51; color:#fff; font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                    <tr>
                        <th style="width:44px;" class="text-center py-2"><input type="checkbox" id="selectAll" title="Select all"></th>
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

        <div id="noInvoicesMsg" class="text-muted small text-center py-2 d-none">No invoices found.</div>

        <button type="button" class="btn btn-sm btn-primary mt-2" id="addSelectedBtn" style="display:none;" onclick="addSelectedToItems()">
            <i class="fas fa-plus me-1"></i>Add Selected to Items
        </button>
    </div>

    <!-- Summary -->
    <div class="section-card">
        <p class="section-title">Payment Summary</p>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Description / Purpose</label>
                <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($voucher['description'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Total Amount (<?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?>) <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="totalAmount" class="form-control fw-bold fs-5" step="0.01" min="0" value="<?= htmlspecialchars($voucher['amount']) ?>" required>
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-12">
                <label class="form-label fw-semibold small">Internal Notes</label>
                <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($voucher['notes'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
        <a href="<?= BASE_URL ?>paymentvoucher/show/<?= $voucher['id'] ?>" class="btn btn-light px-4">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Save Changes
        </button>
    </div>

</form>

<script>
const BASE_URL   = '<?= BASE_URL ?>';
const leadSelect = document.getElementById('leadSelect');

function removeRow(btn) {
    const row = btn.closest('tr');
    row.remove();
    recalcTotal();
    checkEmpty();
}

function checkEmpty() {
    const tbody = document.getElementById('itemsTbody');
    const rows  = tbody.querySelectorAll('tr.item-row');
    let empty   = document.getElementById('emptyRow');
    if (!rows.length) {
        if (!empty) {
            tbody.insertAdjacentHTML('beforeend',
                '<tr id="emptyRow"><td colspan="4" class="text-center text-muted small py-3">No line items.</td></tr>');
        }
    } else if (empty) {
        empty.remove();
    }
}

function addManualRow() {
    const empty = document.getElementById('emptyRow');
    if (empty) empty.remove();
    document.getElementById('itemsTbody').insertAdjacentHTML('beforeend', `
        <tr class="item-row">
            <td><input type="text" name="item_inv_no[]" class="form-control form-control-sm" placeholder="INV-..."></td>
            <td><input type="text" name="item_desc[]" class="form-control form-control-sm" placeholder="Description" required></td>
            <td><input type="number" name="item_amount[]" class="form-control form-control-sm item-amount text-end" step="0.01" min="0" placeholder="0.00" oninput="recalcTotal()"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
        </tr>`);
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.item-amount').forEach(inp => {
        total += parseFloat(inp.value) || 0;
    });
    if (total > 0) document.getElementById('totalAmount').value = total.toFixed(2);
}

function loadInvoices() {
    const leadId = leadSelect.value;
    if (!leadId) { alert('Please select a company first.'); return; }

    document.getElementById('invoiceSection').style.display = 'block';
    document.getElementById('invoiceLoader').classList.remove('d-none');
    document.getElementById('invoiceTableWrap').style.display = 'none';
    document.getElementById('addSelectedBtn').style.display = 'none';
    document.getElementById('noInvoicesMsg').classList.add('d-none');

    fetch(BASE_URL + 'paymentvoucher/invoices?lead_id=' + leadId)
        .then(r => r.json())
        .then(data => {
            document.getElementById('invoiceLoader').classList.add('d-none');
            const tbody = document.getElementById('invoiceTbody');
            tbody.innerHTML = '';

            if (!data.length) {
                document.getElementById('noInvoicesMsg').classList.remove('d-none');
                return;
            }

            document.getElementById('invoiceTableWrap').style.display = 'block';
            document.getElementById('addSelectedBtn').style.display = 'inline-block';

            data.forEach(inv => {
                const balance = parseFloat(inv.balance_due);
                const badge = inv.status === 'Paid'
                    ? '<span class="badge bg-success">Paid</span>'
                    : inv.status === 'Partial'
                    ? '<span class="badge bg-warning text-dark">Partial</span>'
                    : '<span class="badge bg-danger">Unpaid</span>';
                tbody.insertAdjacentHTML('beforeend', `
                    <tr class="inv-row">
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
                        <td class="text-center">${badge}</td>
                    </tr>`);
            });

            document.getElementById('selectAll').addEventListener('change', function() {
                document.querySelectorAll('.inv-check').forEach(cb => cb.checked = this.checked);
            });
        })
        .catch(() => {
            document.getElementById('invoiceLoader').classList.add('d-none');
            document.getElementById('noInvoicesMsg').classList.remove('d-none');
            document.getElementById('noInvoicesMsg').textContent = 'Error loading invoices.';
        });
}

function addSelectedToItems() {
    const empty = document.getElementById('emptyRow');
    if (empty) empty.remove();
    document.querySelectorAll('.inv-check:checked').forEach(cb => {
        const amt = parseFloat(cb.value) || 0;
        document.getElementById('itemsTbody').insertAdjacentHTML('beforeend', `
            <tr class="item-row">
                <td><input type="text" name="item_inv_no[]" class="form-control form-control-sm" value="${escHtml(cb.dataset.inv)}"></td>
                <td><input type="text" name="item_desc[]" class="form-control form-control-sm" value="${escHtml(cb.dataset.desc)}" required></td>
                <td><input type="number" name="item_amount[]" class="form-control form-control-sm item-amount text-end" step="0.01" min="0" value="${amt.toFixed(2)}" oninput="recalcTotal()"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
            </tr>`);
        cb.closest('tr').remove();
    });
    recalcTotal();
    checkEmpty();
    // hide invoice section if no rows left
    if (!document.querySelectorAll('.inv-check').length) {
        document.getElementById('invoiceSection').style.display = 'none';
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
