<div class="row mb-4">
    <div class="col-md-9">
        <form action="" method="GET" class="d-flex gap-2 flex-wrap">
            <input type="month" name="month" class="form-control" value="<?= $current_month ?>" style="max-width: 180px;">
            <input type="date" name="date" class="form-control" value="<?= $current_date ?>" style="max-width: 180px;" placeholder="Filter by Date">
            <select name="category" class="form-select" style="max-width: 200px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $current_category === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-light"><i class="fas fa-filter"></i> Filter</button>
            <button type="submit" name="show_all" value="1" class="btn btn-light" title="Show All Time"><i class="fas fa-list"></i> All</button>
            <a href="<?= BASE_URL ?>expenses" class="btn btn-light" title="Reset to Current Month"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="col-md-3 text-end">
        <a href="<?= BASE_URL ?>expenses/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Expense
        </a>
    </div>
</div>

<!-- Summary (filtered) -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3" style="width:48px;height:48px;background:#fef2f2;">
                    <i class="fas fa-money-bill-wave text-danger"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Expenses <span class="text-muted">(<?= htmlspecialchars($filter_label) ?>)</span></div>
                    <h4 class="fw-bold text-danger mb-0"><?= formatMoney($total_amount) ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3" style="width:48px;height:48px;background:#eef2ff;">
                    <i class="fas fa-receipt" style="color:#6366f1;"></i>
                </div>
                <div>
                    <div class="text-muted small">Entries</div>
                    <h4 class="fw-bold mb-0"><?= count($expenses) ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">By Category</div>
                <?php if (empty($category_totals)): ?>
                    <span class="text-muted small">No expenses in this period.</span>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($category_totals as $cat => $amt): ?>
                        <span class="badge bg-light text-dark border fw-normal py-2 px-3">
                            <?= htmlspecialchars($cat) ?>: <span class="fw-bold text-danger"><?= formatMoney($amt) ?></span>
                        </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3">
        <h5 class="mb-0 fw-bold">Expenses List <small class="text-muted ms-2 fs-6">(<?= $filter_label ?>)</small></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Mode</th>
                        <th>Amount</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($expenses as $expense): 
                        $total += $expense['amount'];
                    ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($expense['expense_date'])) ?></td>
                        <td class="fw-bold">
                            <?= htmlspecialchars($expense['title']) ?>
                            <?php if (!empty($expense['description'])): ?>
                                <br><small class="text-muted fw-normal"><?= htmlspecialchars($expense['description']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($expense['category']) ?></span></td>
                        <td><?= htmlspecialchars($expense['payment_mode']) ?></td>
                        <td class="fw-bold text-danger">-<?= formatMoney($expense['amount']) ?></td>
                        <td><small class="text-muted"><?= htmlspecialchars($expense['created_by_name']) ?></small></td>
                        <td>
                            <a href="<?= BASE_URL ?>expenses/edit/<?= $expense['id'] ?>" class="btn btn-sm btn-light text-primary"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>expenses/delete/<?= $expense['id'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Delete this expense?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($expenses)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No expenses found for this period.</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($expenses)): ?>
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold text-danger">-<?= formatMoney($total) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
