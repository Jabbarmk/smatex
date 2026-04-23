<div class="row mb-3 align-items-center">
    <div class="col">
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="leadSearch" class="form-control border-start-0 ps-0"
                   placeholder="Search by company, name, email, status..."
                   oninput="filterTable('leadSearch','leadTable','leadCount')">
            <button class="btn btn-outline-secondary" onclick="clearSearch('leadSearch','leadTable','leadCount')" title="Clear">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="col-auto">
        <a href="<?= BASE_URL ?>leads/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Lead
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">All Leads</h5>
        <small class="text-muted" id="leadCount"><?= count($leads) ?> records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0" id="leadTable">
                <thead class="bg-light">
                    <tr>
                        <th>Lead Name</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Emirate</th>
                        <th>Status</th>
                        <th>Sales Mgr</th>
                        <th>Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($lead['lead_name']) ?></td>
                        <td><?= htmlspecialchars($lead['company_name']) ?></td>
                        <td>
                            <div class="small"><i class="fas fa-phone me-1 text-muted"></i> <?= htmlspecialchars($lead['phone']) ?></div>
                            <div class="small"><i class="fas fa-envelope me-1 text-muted"></i> <?= htmlspecialchars($lead['email']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($lead['emirates']) ?></td>
                        <td>
                            <?php
                            $statusClass = match($lead['status']) {
                                'New' => 'bg-info text-white',
                                'Won' => 'bg-success text-white',
                                'Lost' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                            ?>
                            <span class="badge <?= $statusClass ?> rounded-pill px-3 py-2"><?= $lead['status'] ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2 bg-light text-primary fw-bold text-center rounded-circle" style="width:30px;height:30px;line-height:30px;">
                                    <?= substr($lead['sales_manager_name'] ?? 'U', 0, 1) ?>
                                </div>
                                <?= htmlspecialchars($lead['sales_manager_name']) ?>
                            </div>
                        </td>
                        <td class="fw-bold text-success"><?= formatMoney($lead['expected_value']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>leads/edit/<?= $lead['id'] ?>" class="btn btn-sm btn-light text-primary"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>leads/delete/<?= $lead['id'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div id="leadEmpty" class="text-center text-muted py-4 d-none">
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

