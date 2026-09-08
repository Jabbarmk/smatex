<?php
$s = $summaryStats;
function fmtK($n) {
    if ($n >= 1000000) return number_format($n/1000000, 1) . 'M';
    if ($n >= 1000)    return number_format($n/1000, 1) . 'K';
    return number_format($n, 0);
}
?>

<!-- ===== SUMMARY CARDS ===== -->
<div class="row g-3 mb-4" id="summaryCards">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3 px-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">Total Invoiced</div>
                <div class="fw-bold mt-1" id="card-total" style="font-size:1.25rem;color:#3a3f51;"><?= formatMoney($s['total_all_time']) ?></div>
                <div id="card-total-sub" class="text-muted" style="font-size:.72rem;">All time</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3 px-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">This Month</div>
                <div class="fw-bold mt-1" id="card-month" style="font-size:1.25rem;color:#e8602c;"><?= formatMoney($s['this_month']) ?></div>
                <div class="text-muted" style="font-size:.72rem;"><?= date('F Y') ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100" style="border-left:3px solid #198754!important;">
            <div class="card-body py-3 px-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">Paid</div>
                <div class="fw-bold mt-1" id="card-paid-amount" style="font-size:1.25rem;color:#198754;"><?= formatMoney($s['amount_paid']) ?></div>
                <div id="card-paid-count" class="text-muted" style="font-size:.72rem;"><?= $s['count_paid'] ?> invoice<?= $s['count_paid'] != 1 ? 's' : '' ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100" style="border-left:3px solid #dc3545!important;">
            <div class="card-body py-3 px-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">Unpaid</div>
                <div class="fw-bold mt-1" id="card-unpaid-amount" style="font-size:1.25rem;color:#dc3545;"><?= formatMoney($s['amount_unpaid']) ?></div>
                <div id="card-unpaid-count" class="text-muted" style="font-size:.72rem;"><?= $s['count_unpaid'] ?> invoice<?= $s['count_unpaid'] != 1 ? 's' : '' ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100" style="border-left:3px solid #ffc107!important;">
            <div class="card-body py-3 px-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">Partial</div>
                <div class="fw-bold mt-1" id="card-partial-amount" style="font-size:1.25rem;color:#856404;"><?= formatMoney($s['amount_partial']) ?></div>
                <div id="card-partial-count" class="text-muted" style="font-size:.72rem;"><?= $s['count_partial'] ?> invoice<?= $s['count_partial'] != 1 ? 's' : '' ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100" style="border-left:3px solid #6c757d!important;">
            <div class="card-body py-3 px-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">Outstanding</div>
                <div class="fw-bold mt-1" id="card-outstanding" style="font-size:1.25rem;color:#6c757d;"><?= formatMoney($s['outstanding']) ?></div>
                <div class="text-muted" style="font-size:.72rem;">Balance due</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABS ===== -->
<ul class="nav nav-tabs mb-0 border-bottom-0" id="invTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-semibold" id="tab-invoices" data-bs-toggle="tab" data-bs-target="#pane-invoices" type="button">
            <i class="fas fa-file-invoice me-1"></i> Invoices
            <span class="badge bg-secondary ms-1" style="font-size:.68rem;"><?= count($invoices) ?></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-semibold" id="tab-drafts" data-bs-toggle="tab" data-bs-target="#pane-drafts" type="button">
            <i class="fas fa-file-alt me-1 text-muted"></i> Drafts
            <span class="badge bg-secondary ms-1" style="font-size:.68rem;"><?= count($drafts) ?></span>
        </button>
    </li>
    <li class="nav-item ms-auto d-flex align-items-center gap-2 pb-1" id="tab-actions">
        <a href="<?= BASE_URL ?>invoices/create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> New Invoice
        </a>
        <a href="<?= BASE_URL ?>invoices/create/1" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-file-alt"></i> New Draft
        </a>
    </li>
</ul>

<div class="tab-content">

    <!-- ===== INVOICES TAB ===== -->
    <div class="tab-pane fade show active" id="pane-invoices">
        <div class="card border-0 shadow-sm" style="border-top-left-radius:0;">
            <!-- Filters -->
            <div class="card-header bg-white py-2 border-bottom">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="input-group input-group-sm" style="max-width:320px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="invSearch" class="form-control border-start-0 ps-0" placeholder="Search name, company, invoice #…" oninput="applyFilters()">
                        </div>
                    </div>
                    <div class="col-auto">
                        <select id="invClient" class="form-select form-select-sm" onchange="applyFilters()" style="min-width:160px;">
                            <option value="">All Clients</option>
                            <?php foreach ($uniqueClients as $c): ?>
                                <option value="<?= htmlspecialchars($c['lead_name']) ?>"><?= htmlspecialchars($c['lead_name']) ?><?= $c['company_name'] ? ' – ' . htmlspecialchars($c['company_name']) : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="month" id="invMonth" class="form-control form-control-sm" onchange="applyFilters()" style="min-width:140px;">
                    </div>
                    <div class="col-auto">
                        <select id="invStatus" class="form-select form-select-sm" onchange="applyFilters()" style="min-width:120px;">
                            <option value="">All Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Partial">Partial</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()" title="Clear filters"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="col-auto ms-2">
                        <small class="text-muted" id="invCount"><?= count($invoices) ?> records</small>
                    </div>
                </div>
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
                            <tr data-client="<?= htmlspecialchars($inv['lead_name']) ?>"
                                data-month="<?= date('Y-m', strtotime($inv['created_at'])) ?>"
                                data-status="<?= htmlspecialchars($inv['status']) ?>"
                                data-amount="<?= $inv['grand_total'] ?>">
                                <td class="fw-bold text-primary"><?= htmlspecialchars($inv['invoice_no']) ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($inv['lead_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($inv['company_name']) ?></small>
                                </td>
                                <td><?= date('d M Y', strtotime($inv['created_at'])) ?></td>
                                <td><?= date('d M Y', strtotime($inv['due_date'])) ?></td>
                                <td class="fw-bold"><?= formatMoney($inv['grand_total']) ?></td>
                                <td>
                                    <?php $statusClass = match($inv['status']) {
                                        'Paid'    => 'bg-success text-white',
                                        'Partial' => 'bg-warning text-dark',
                                        default   => 'bg-danger text-white',
                                    }; ?>
                                    <span class="badge <?= $statusClass ?>"><?= $inv['status'] ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>invoices/show/<?= $inv['id'] ?>" class="btn btn-sm btn-light" title="View"><i class="fas fa-eye"></i></a>
                                    <?php if ($inv['status'] !== 'Paid'): ?>
                                        <a href="<?= BASE_URL ?>receipts/create/<?= $inv['id'] ?>" class="btn btn-sm btn-light text-success" title="Record Payment"><i class="fas fa-money-bill-wave"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>invoices/edit/<?= $inv['id'] ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="<?= BASE_URL ?>invoices/delete/<?= $inv['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this invoice?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div id="invEmpty" class="text-center text-muted py-4 d-none">
                        <i class="fas fa-search fa-2x mb-2 opacity-25"></i><br>No records match your filters.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DRAFTS TAB ===== -->
    <div class="tab-pane fade" id="pane-drafts">
        <div class="card border-0 shadow-sm" style="border-top-left-radius:0;">
            <div class="card-header bg-white py-2 border-bottom d-flex align-items-center gap-2">
                <i class="fas fa-info-circle text-muted"></i>
                <span class="text-muted small">Draft invoices are <strong>excluded from revenue stats</strong>. Use <strong>Promote to Invoice</strong> to convert a draft into a real invoice.</span>
                <div class="ms-auto">
                    <input type="text" id="draftSearch" class="form-control form-control-sm" placeholder="Search drafts…" oninput="filterDrafts()" style="max-width:220px;">
                </div>
                <small class="text-muted ms-2" id="draftCount"><?= count($drafts) ?> draft<?= count($drafts) != 1 ? 's' : '' ?></small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0" id="draftTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Draft #</th>
                                <th>Client / Lead</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drafts as $d): ?>
                            <tr>
                                <td class="fw-bold" style="color:#6c757d;"><?= htmlspecialchars($d['invoice_no']) ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($d['lead_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($d['company_name']) ?></small>
                                </td>
                                <td><?= date('d M Y', strtotime($d['created_at'])) ?></td>
                                <td><?= date('d M Y', strtotime($d['due_date'])) ?></td>
                                <td class="fw-bold"><?= formatMoney($d['grand_total']) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>invoices/show/<?= $d['id'] ?>" class="btn btn-sm btn-light" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="<?= BASE_URL ?>invoices/promote/<?= $d['id'] ?>" class="btn btn-sm btn-light text-success" title="Promote to Invoice" onclick="return confirm('Convert this draft to a real invoice? A new INV number will be assigned.')"><i class="fas fa-check-circle"></i></a>
                                    <a href="<?= BASE_URL ?>invoices/edit/<?= $d['id'] ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="<?= BASE_URL ?>invoices/delete/<?= $d['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this draft?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($drafts)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2 opacity-25"></i><br>No draft invoices yet.
                    </div>
                    <?php endif; ?>
                    <div id="draftEmpty" class="text-center text-muted py-4 d-none">
                        <i class="fas fa-search fa-2x mb-2 opacity-25"></i><br>No drafts match your search.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// ── Currency formatter (mirrors PHP formatMoney) ──────────────────────────
const _cx = <?= currencySettingsJson() ?>;
const _curMonth = '<?= date('Y-m') ?>';

function fmtMoney(n) {
    let thou = _cx.thousands_separator;
    if (thou === 'space') thou = ' ';
    if (thou === 'none')  thou = '';
    const dec   = _cx.decimal_separator;
    const places = parseInt(_cx.decimal_places);
    const parts = n.toFixed(places).split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thou);
    const num = parts.length > 1 ? parts[0] + dec + parts[1] : parts[0];
    return _cx.position === 'after' ? num + ' ' + _cx.symbol : _cx.symbol + num;
}

// ── Update summary cards from visible rows ────────────────────────────────
function updateCards(rows, isFiltered) {
    let total = 0, thisMonth = 0;
    let paidAmt = 0, paidCnt = 0;
    let unpaidAmt = 0, unpaidCnt = 0;
    let partialAmt = 0, partialCnt = 0;
    let outstanding = 0;

    rows.forEach(function(row) {
        if (row.style.display === 'none') return;
        const amt    = parseFloat(row.dataset.amount) || 0;
        const status = row.dataset.status || '';
        const month  = row.dataset.month  || '';

        total += amt;
        if (month === _curMonth) thisMonth += amt;

        if (status === 'Paid')    { paidAmt    += amt; paidCnt++;    }
        if (status === 'Unpaid')  { unpaidAmt  += amt; unpaidCnt++;  outstanding += amt; }
        if (status === 'Partial') { partialAmt += amt; partialCnt++; outstanding += amt; }
    });

    document.getElementById('card-total').textContent        = fmtMoney(total);
    document.getElementById('card-month').textContent        = fmtMoney(thisMonth);
    document.getElementById('card-paid-amount').textContent  = fmtMoney(paidAmt);
    document.getElementById('card-paid-count').textContent   = paidCnt + ' invoice' + (paidCnt !== 1 ? 's' : '');
    document.getElementById('card-unpaid-amount').textContent  = fmtMoney(unpaidAmt);
    document.getElementById('card-unpaid-count').textContent   = unpaidCnt + ' invoice' + (unpaidCnt !== 1 ? 's' : '');
    document.getElementById('card-partial-amount').textContent = fmtMoney(partialAmt);
    document.getElementById('card-partial-count').textContent  = partialCnt + ' invoice' + (partialCnt !== 1 ? 's' : '');
    document.getElementById('card-outstanding').textContent   = fmtMoney(outstanding);
    document.getElementById('card-total-sub').textContent    = isFiltered ? 'Filtered' : 'All time';
}

// ── Main filter function ──────────────────────────────────────────────────
function applyFilters() {
    const q      = document.getElementById('invSearch').value.toLowerCase().trim();
    const client = document.getElementById('invClient').value;
    const month  = document.getElementById('invMonth').value;
    const status = document.getElementById('invStatus').value;
    const tbody  = document.querySelector('#invoiceTable tbody');
    const rows   = Array.from(tbody.querySelectorAll('tr'));
    let visible  = 0;

    const isFiltered = q || client || month || status;

    rows.forEach(function(row) {
        const text    = row.innerText.toLowerCase();
        const rClient = row.dataset.client || '';
        const rMonth  = row.dataset.month  || '';
        const rStatus = row.dataset.status || '';

        const ok = (!q      || text.includes(q))
                && (!client || rClient === client)
                && (!month  || rMonth  === month)
                && (!status || rStatus === status);

        row.style.display = ok ? '' : 'none';
        if (ok) visible++;
    });

    document.getElementById('invCount').textContent = visible + ' record' + (visible !== 1 ? 's' : '');
    document.getElementById('invEmpty').classList.toggle('d-none', visible > 0);

    updateCards(rows, !!isFiltered);
}

function clearFilters() {
    document.getElementById('invSearch').value = '';
    document.getElementById('invClient').value = '';
    document.getElementById('invMonth').value  = '';
    document.getElementById('invStatus').value = '';
    applyFilters();
}

function filterDrafts() {
    const q     = document.getElementById('draftSearch').value.toLowerCase().trim();
    const tbody = document.querySelector('#draftTable tbody');
    const rows  = tbody.querySelectorAll('tr');
    let visible = 0;
    rows.forEach(function(row) {
        const show = !q || row.innerText.toLowerCase().includes(q);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('draftCount').textContent = visible + ' draft' + (visible !== 1 ? 's' : '');
    document.getElementById('draftEmpty').classList.toggle('d-none', visible > 0);
}

// ── Keep active tab across page loads ─────────────────────────────────────
(function() {
    const saved = sessionStorage.getItem('invActiveTab');
    if (saved) {
        const btn = document.querySelector('[data-bs-target="' + saved + '"]');
        if (btn) bootstrap.Tab.getOrCreateInstance(btn).show();
    }
    document.querySelectorAll('#invTabs button[data-bs-toggle="tab"]').forEach(function(btn) {
        btn.addEventListener('shown.bs.tab', function(e) {
            sessionStorage.setItem('invActiveTab', e.target.dataset.bsTarget);
        });
    });
})();
</script>
