<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Issue, download and verify employee certificates.</p>
    </div>
    <a href="<?= BASE_URL ?>certificates/create" class="btn btn-primary">
        <i class="fas fa-certificate me-1"></i> New Certificate
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
        <h5 class="mb-0 fw-bold"><i class="fas fa-award me-2 text-primary"></i>All Certificates</h5>
        <small class="text-muted"><?= count($certificates) ?> issued</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Cert No</th>
                        <th>Type</th>
                        <th>Candidate</th>
                        <th>Subject</th>
                        <th>Issue Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($certificates)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-5">
                        No certificates yet. <a href="<?= BASE_URL ?>certificates/create">Issue one now</a>.
                    </td></tr>
                <?php else: foreach ($certificates as $c): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($c['certificate_no']) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($c['certificate_type']) ?></span></td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($c['candidate_name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($c['designation']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($c['subject']) ?></td>
                        <td><?= date('d M Y', strtotime($c['issue_date'])) ?></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>certificates/show/<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary" title="View / Print"><i class="fas fa-eye"></i></a>
                            <a href="<?= BASE_URL ?>certificates/edit/<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>certificates/verify/<?= $c['certificate_slug'] ?>" target="_blank" class="btn btn-sm btn-outline-success" title="Public Verify Link"><i class="fas fa-link"></i></a>
                            <a href="<?= BASE_URL ?>certificates/delete/<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this certificate?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
