<div class="row mb-4">
    <div class="col-md-8">
        <form action="" method="GET" class="d-flex gap-2">
            <input type="month" name="month" class="form-control" value="<?= $current_month ?>" style="max-width: 200px;">
            <input type="date" name="date" class="form-control" value="<?= $current_date ?>" style="max-width: 200px;" placeholder="Filter by Date">
            <button type="submit" class="btn btn-light"><i class="fas fa-filter"></i> Filter</button>
            <a href="<?= BASE_URL ?>expenses?show_all=1" class="btn btn-light" title="Show All"><i class="fas fa-list"></i> All</a>
            <a href="<?= BASE_URL ?>expenses" class="btn btn-light" title="Reset to Current Month"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= BASE_URL ?>expenses/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Expense
        </a>
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
