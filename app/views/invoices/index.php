<div class="row mb-3 align-items-center">
    <div class="col">
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="invoiceSearch" class="form-control border-start-0 ps-0"
                   placeholder="Search by company name, client or invoice #..."
                   oninput="filterTable('invoiceSearch','invoiceTable','invoiceCount')">
            <button class="btn btn-outline-secondary" onclick="clearSearch('invoiceSearch','invoiceTable','invoiceCount')" title="Clear">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="col-auto">
        <a href="<?= BASE_URL ?>invoices/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Invoice
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Invoices &amp; Revenue</h5>
        <small class="text-muted" id="invoiceCount"><?= count($invoices) ?> records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0" id="invoiceTable">
                <thead class="bg-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Client / Lead</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($inv['invoice_no']) ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($inv['lead_name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($inv['company_name']) ?></small>
                        </td>
                        <td><?= date('d M Y', strtotime($inv['created_at'])) ?></td>
                        <td><?= date('d M Y', strtotime($inv['due_date'])) ?></td>
                        <td class="fw-bold"><?= formatMoney($inv['grand_total']) ?></td>
                        <td>
                            <?php 
                            $statusClass = match($inv['status']) {
                                'Paid' => 'bg-success text-white',
                                'Partial' => 'bg-warning text-dark',
                                default => 'bg-danger text-white'
                            };
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $inv['status'] ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>invoices/show/<?= $inv['id'] ?>" class="btn btn-sm btn-light" title="View"><i class="fas fa-eye"></i></a>
                            <?php if($inv['status'] !== 'Paid'): ?>
                                <a href="<?= BASE_URL ?>receipts/create/<?= $inv['id'] ?>" class="btn btn-sm btn-light text-success" title="Record Payment"><i class="fas fa-money-bill-wave"></i></a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>invoices/edit/<?= $inv['id'] ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>invoices/delete/<?= $inv['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div id="invoiceEmpty" class="text-center text-muted py-4 d-none">
                <i class="fas fa-search fa-2x mb-2 opacity-25"></i><br>No records match your search.
            </div>
        </div>
    </div>
</div>

<script>
function filterTable(inputId, tableId, countId) {
    const q     = document.getElementById(inputId).value.toLowerCase().trim();
    const tbody = document.querySelector('#' + tableId + ' tbody');
    const rows  = tbody.querySelectorAll('tr');
    const empty = document.getElementById(tableId.replace('Table','Empty'));
    let visible = 0;
    rows.forEach(function(row) {
        const text = row.innerText.toLowerCase();
        const show = !q || text.includes(q);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById(countId).textContent = visible + ' record' + (visible !== 1 ? 's' : '');
    if (empty) empty.classList.toggle('d-none', visible > 0);
}
function clearSearch(inputId, tableId, countId) {
    document.getElementById(inputId).value = '';
    filterTable(inputId, tableId, countId);
    document.getElementById(inputId).focus();
}
</script>
