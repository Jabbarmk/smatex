<div class="row mb-4">
    <div class="col-md-8">
        <form action="" method="GET" class="d-flex gap-2">
            <input type="month" name="month" class="form-control" value="<?= $current_month ?>" style="max-width: 200px;">
            <input type="date" name="date" class="form-control" value="<?= $current_date ?>" style="max-width: 200px;" placeholder="Filter by Date">
            <button type="submit" class="btn btn-light"><i class="fas fa-filter"></i> Filter</button>
            <a href="<?= BASE_URL ?>receipts?show_all=1" class="btn btn-light" title="Show All"><i class="fas fa-list"></i> All</a>
            <a href="<?= BASE_URL ?>receipts" class="btn btn-light" title="Reset to Current Month"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= BASE_URL ?>receipts/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Receipt
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3">
        <h5 class="mb-0 fw-bold">Receipts List <small class="text-muted ms-2 fs-6">(<?= $filter_label ?>)</small></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Receipt No</th>
                        <th>Invoice</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($receipts)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No receipts found.</td></tr>
                    <?php else: ?>
                        <?php foreach($receipts as $receipt): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= $receipt['receipt_no'] ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>invoices/show/<?= $receipt['invoice_id'] ?>" class="text-decoration-none">
                                    <?= $receipt['invoice_no'] ?>
                                </a>
                            </td>
                            <td><?= $receipt['lead_name'] ? $receipt['lead_name'] : $receipt['client_details'] ?></td>
                            <td><?= date('d M Y', strtotime($receipt['payment_date'])) ?></td>
                            <td><?= $receipt['payment_mode'] ?></td>
                            <td class="text-end fw-bold text-success"><?= formatMoney($receipt['amount_paid']) ?></td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>receipts/show/<?= $receipt['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Preview / Print"><i class="fas fa-eye"></i></a>
                                <a href="<?= BASE_URL ?>receipts/edit/<?= $receipt['id'] ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL ?>receipts/delete/<?= $receipt['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
