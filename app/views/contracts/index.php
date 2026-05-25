<div class="row mb-3 align-items-center">
    <div class="col">
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="contractSearch" class="form-control border-start-0 ps-0"
                   placeholder="Search contracts..."
                   oninput="filterTable('contractSearch','contractTable','contractCount')">
            <button class="btn btn-outline-secondary" onclick="clearSearch('contractSearch','contractTable','contractCount')" title="Clear">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="col-auto">
        <a href="<?= BASE_URL ?>contracts/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Contract
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">All Contracts</h5>
        <small class="text-muted" id="contractCount"><?= count($contracts) ?> records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0" id="contractTable">
                <thead class="bg-light">
                    <tr>
                        <th>Contract #</th>
                        <th>Title</th>
                        <th>First Party</th>
                        <th>Second Party</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contracts as $c): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($c['contract_no']) ?></td>
                        <td><?= htmlspecialchars($c['title']) ?></td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($c['first_party_name']) ?></div>
                            <?php if ($c['first_party_representative']): ?>
                                <small class="text-muted"><?= htmlspecialchars($c['first_party_representative']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($c['second_party_name']) ?></div>
                            <?php if ($c['second_party_representative']): ?>
                                <small class="text-muted"><?= htmlspecialchars($c['second_party_representative']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($c['contract_type']) ?></span></td>
                        <td><?= $c['start_date'] ? date('d M Y', strtotime($c['start_date'])) : '—' ?></td>
                        <td><?= $c['end_date'] ? date('d M Y', strtotime($c['end_date'])) : '—' ?></td>
                        <td><?= $c['value'] > 0 ? formatMoney($c['value']) : '—' ?></td>
                        <td>
                            <?php
                            $cls = match($c['status']) {
                                'Active'     => 'bg-success text-white',
                                'Expired'    => 'bg-danger text-white',
                                'Terminated' => 'bg-dark text-white',
                                default      => 'bg-warning text-dark',
                            };
                            ?>
                            <span class="badge <?= $cls ?>"><?= $c['status'] ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>contracts/show/<?= $c['id'] ?>" class="btn btn-sm btn-light" title="View"><i class="fas fa-eye"></i></a>
                            <a href="<?= BASE_URL ?>contracts/edit/<?= $c['id'] ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>contracts/delete/<?= $c['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this contract?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($contracts)): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fas fa-file-contract fa-3x mb-3 opacity-25"></i><br>
                            No contracts yet. <a href="<?= BASE_URL ?>contracts/create">Create one.</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="contractEmpty" class="text-center text-muted py-4 d-none">
                <i class="fas fa-search fa-2x mb-2 opacity-25"></i><br>No records match your search.
            </div>
        </div>
    </div>
</div>

<script>
function filterTable(inputId, tableId, countId) {
    const q = document.getElementById(inputId).value.toLowerCase().trim();
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    const empty = document.getElementById('contractEmpty');
    let visible = 0;
    rows.forEach(row => {
        const show = !q || row.innerText.toLowerCase().includes(q);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById(countId).textContent = visible + ' record' + (visible !== 1 ? 's' : '');
    if (empty) empty.classList.toggle('d-none', visible > 0);
}
function clearSearch(inputId, tableId, countId) {
    document.getElementById(inputId).value = '';
    filterTable(inputId, tableId, countId);
}
</script>
