<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">All salary payment vouchers.</p>
    <a href="<?= BASE_URL ?>salary/create" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Salary Voucher
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
        <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Salary Payments</h5>
        <small class="text-muted"><?= count($payments) ?> records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Voucher No</th>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Payment Date</th>
                        <th>Mode</th>
                        <th class="text-end">Net Salary</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">No salary vouchers found. <a href="<?= BASE_URL ?>salary/create">Create one?</a></td></tr>
                    <?php else: ?>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($p['voucher_no']) ?></td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($p['full_name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($p['employee_no']) ?> · <?= htmlspecialchars($p['designation']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($p['payment_month']) ?> <?= $p['payment_year'] ?></td>
                        <td><?= date('d M Y', strtotime($p['payment_date'])) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $p['payment_mode'] ?></span></td>
                        <td class="text-end fw-bold text-success"><?= formatMoney($p['net_salary']) ?></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>salary/show/<?= $p['id'] ?>" class="btn btn-sm btn-light" title="View Voucher"><i class="fas fa-eye"></i></a>
                            <a href="<?= BASE_URL ?>salary/delete/<?= $p['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this voucher?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
