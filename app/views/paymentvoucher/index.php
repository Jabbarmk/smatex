<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">All payment vouchers issued</p>
    </div>
    <a href="<?= BASE_URL ?>paymentvoucher/create" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Payment Voucher
    </a>
</div>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Failed to save payment voucher. Please try again.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#3a3f51; color:#fff; font-size:.75rem; text-transform:uppercase; letter-spacing:.5px;">
                    <tr>
                        <th class="py-3 ps-4">#</th>
                        <th class="py-3">Voucher No</th>
                        <th class="py-3">Company</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Payment Mode</th>
                        <th class="py-3 text-end pe-4">Amount (<?= htmlspecialchars($settings['currency_symbol'] ?? 'AED') ?>)</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($vouchers)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-file-invoice fa-2x mb-2 d-block opacity-25"></i>
                            No payment vouchers found. <a href="<?= BASE_URL ?>paymentvoucher/create">Create one now.</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vouchers as $i => $v): ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>paymentvoucher/show/<?= $v['id'] ?>" class="fw-semibold text-primary text-decoration-none">
                                <?= htmlspecialchars($v['voucher_no']) ?>
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($v['company_name'] ?: ($v['lead_name'] ?? '—')) ?></div>
                            <?php if (!empty($v['company_name']) && !empty($v['lead_name'])): ?>
                            <small class="text-muted"><?= htmlspecialchars($v['lead_name']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted"><?= date('d M Y', strtotime($v['payment_date'])) ?></td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border">
                                <?= htmlspecialchars($v['payment_mode']) ?>
                            </span>
                        </td>
                        <td class="text-end pe-4 fw-bold"><?= formatMoney($v['amount']) ?></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>paymentvoucher/show/<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= BASE_URL ?>paymentvoucher/edit/<?= $v['id'] ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?= $v['id'] ?>, '<?= htmlspecialchars($v['voucher_no']) ?>')"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Delete Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-muted">
                Are you sure you want to delete voucher <strong id="deleteVoucherNo"></strong>? This cannot be undone.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteConfirmBtn" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, no) {
    document.getElementById('deleteVoucherNo').textContent = no;
    document.getElementById('deleteConfirmBtn').href = '<?= BASE_URL ?>paymentvoucher/delete/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
