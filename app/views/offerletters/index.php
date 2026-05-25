<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Offer letter deleted successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Create and manage employment offer letters.</p>
    <a href="<?= BASE_URL ?>offerletters/create" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Offer Letter
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
        <h5 class="mb-0 fw-bold"><i class="fas fa-file-signature me-2 text-primary"></i>Offer Letters</h5>
        <small class="text-muted"><?= count($offers) ?> records</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Offer No</th>
                        <th>Candidate</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Offer Date</th>
                        <th>Joining Date</th>
                        <th class="text-end">Total Package</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($offers)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            No offer letters yet. <a href="<?= BASE_URL ?>offerletters/create">Create one?</a>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($offers as $o): ?>
                    <?php
                        $total = $o['basic_salary'] + $o['housing_allowance'] + $o['transport_allowance'] + $o['other_allowance'];
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($o['offer_no']) ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:50%;background:#667eea22;display:flex;align-items:center;justify-content:center;font-weight:700;color:#667eea;flex-shrink:0;">
                                    <?= strtoupper(substr($o['candidate_name'], 0, 1)) ?>
                                </div>
                                <span class="fw-semibold"><?= htmlspecialchars($o['candidate_name']) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($o['designation']) ?></td>
                        <td><?= htmlspecialchars($o['department']) ?></td>
                        <td><?= $o['offer_date'] ? date('d M Y', strtotime($o['offer_date'])) : '—' ?></td>
                        <td><?= $o['joining_date'] ? date('d M Y', strtotime($o['joining_date'])) : '—' ?></td>
                        <td class="text-end fw-semibold"><?= formatMoney($total) ?></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>offerletters/show/<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary" title="View / Print"><i class="fas fa-eye"></i></a>
                            <a href="<?= BASE_URL ?>offerletters/edit/<?= $o['id'] ?>" class="btn btn-sm btn-light text-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>offerletters/delete/<?= $o['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this offer letter?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
