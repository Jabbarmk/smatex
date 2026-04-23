<div class="row mb-3 align-items-center">
    <div class="col">
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="quotationSearch" class="form-control border-start-0 ps-0"
                   placeholder="Search by company name, client or quotation #..."
                   oninput="filterTable('quotationSearch','quotationTable','quotationCount')">
            <button class="btn btn-outline-secondary" onclick="clearSearch('quotationSearch','quotationTable','quotationCount')" title="Clear">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="col-auto">
        <a href="<?= BASE_URL ?>quotations/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Quotation
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Quotations</h5>
        <small class="text-muted" id="quotationCount"><?= count($quotations) ?> records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0" id="quotationTable">
                <thead class="bg-light">
                    <tr>
                        <th>Quotation #</th>
                        <th>Client / Lead</th>
                        <th>Date</th>
                        <th>Valid Until</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotations as $quote): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($quote['quotation_no']) ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($quote['lead_name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($quote['company_name']) ?></small>
                        </td>
                        <td><?= date('d M Y', strtotime($quote['created_at'])) ?></td>
                        <td><?= date('d M Y', strtotime($quote['valid_until'])) ?></td>
                        <td class="fw-bold"><?= formatMoney($quote['grand_total']) ?></td>
                        <td>
                            <?php 
                            $statusClass = match($quote['status']) {
                                'Approved' => 'bg-success text-white',
                                'Sent' => 'bg-info text-dark',
                                'Rejected' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $quote['status'] ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>quotations/show/<?= $quote['id'] ?>" class="btn btn-sm btn-light" title="View"><i class="fas fa-eye"></i></a>
                            <a href="<?= BASE_URL ?>quotations/edit/<?= $quote['id'] ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>quotations/delete/<?= $quote['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div id="quotationEmpty" class="text-center text-muted py-4 d-none">
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

