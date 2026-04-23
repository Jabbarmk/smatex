<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Fill in the details below to issue a new certificate.</p>
    <a href="<?= BASE_URL ?>certificates" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<form action="<?= BASE_URL ?>certificates/store" method="POST" class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Certificate No.</label>
                <input type="text" name="certificate_no" class="form-control" value="<?= htmlspecialchars($certificate_no) ?>" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Certificate Type <span class="text-danger">*</span></label>
                <select name="certificate_type" class="form-select" required>
                    <?php foreach ($types as $t): ?>
                        <option value="<?= $t ?>"><?= $t ?> Certificate</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Candidate Full Name <span class="text-danger">*</span></label>
                <input type="text" name="candidate_name" class="form-control" placeholder="e.g. Mohammed Ahmed" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Designation / Role</label>
                <input type="text" name="designation" class="form-control" placeholder="e.g. Software Intern">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Subject / Program <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control" placeholder="e.g. Full Stack Web Development Internship" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Duration From</label>
                <input type="date" name="duration_from" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Duration To</label>
                <input type="date" name="duration_to" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Description / Remarks</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Additional text that will appear on the certificate body. Leave blank to use an auto-generated premium paragraph."></textarea>
                <small class="text-muted">Tip: leave blank to use an elegant auto-generated paragraph based on the type, duration and subject.</small>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Issued By (Name)</label>
                <input type="text" name="issued_by" class="form-control" placeholder="e.g. John Smith">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Issued By (Title)</label>
                <input type="text" name="issued_by_title" class="form-control" placeholder="e.g. Managing Director">
            </div>
        </div>
    </div>
    <div class="card-footer bg-white text-end p-3">
        <a href="<?= BASE_URL ?>certificates" class="btn btn-light me-2">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Generate Certificate</button>
    </div>
</form>
