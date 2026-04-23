<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Candidate name is required.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Update the certificate details below.</p>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>certificates/show/<?= $cert['id'] ?>" class="btn btn-light"><i class="fas fa-eye me-1"></i> View</a>
        <a href="<?= BASE_URL ?>certificates" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
</div>

<form action="<?= BASE_URL ?>certificates/update/<?= $cert['id'] ?>" method="POST" class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label fw-semibold">Certificate No. <span class="text-danger">*</span></label>
                <input type="text" name="certificate_no" class="form-control" value="<?= htmlspecialchars($cert['certificate_no']) ?>" required>
                <small class="text-muted">You may edit the certificate number.</small>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Certificate Type <span class="text-danger">*</span></label>
                <select name="certificate_type" class="form-select" required>
                    <?php foreach ($types as $t): ?>
                        <option value="<?= $t ?>" <?= $cert['certificate_type'] === $t ? 'selected' : '' ?>><?= $t ?> Certificate</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                <input type="date" name="issue_date" class="form-control" value="<?= htmlspecialchars($cert['issue_date']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Candidate Full Name <span class="text-danger">*</span></label>
                <input type="text" name="candidate_name" class="form-control" value="<?= htmlspecialchars($cert['candidate_name']) ?>" required>
                <small class="text-muted">Changing the name will regenerate the verify URL slug.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Designation / Role</label>
                <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($cert['designation']) ?>">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Subject / Program <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control" value="<?= htmlspecialchars($cert['subject']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Duration From</label>
                <input type="date" name="duration_from" class="form-control" value="<?= htmlspecialchars($cert['duration_from'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Duration To</label>
                <input type="date" name="duration_to" class="form-control" value="<?= htmlspecialchars($cert['duration_to'] ?? '') ?>">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Description / Remarks</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($cert['description']) ?></textarea>
                <small class="text-muted">Leave blank to use an auto-generated paragraph based on the type, duration and subject.</small>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Issued By (Name)</label>
                <input type="text" name="issued_by" class="form-control" value="<?= htmlspecialchars($cert['issued_by']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Issued By (Title)</label>
                <input type="text" name="issued_by_title" class="form-control" value="<?= htmlspecialchars($cert['issued_by_title']) ?>">
            </div>

        </div>
    </div>
    <div class="card-footer bg-white text-end p-3 d-flex justify-content-between align-items-center">
        <a href="<?= BASE_URL ?>certificates/delete/<?= $cert['id'] ?>" class="btn btn-outline-danger btn-sm"
           onclick="return confirm('Permanently delete this certificate?')">
            <i class="fas fa-trash me-1"></i> Delete
        </a>
        <div>
            <a href="<?= BASE_URL ?>certificates" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Changes</button>
        </div>
    </div>
</form>
