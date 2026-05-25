<?php $o = $offer; ?>

<div class="mb-4">
    <a href="<?= BASE_URL ?>offerletters/show/<?= $o['id'] ?>" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<form method="POST" action="<?= BASE_URL ?>offerletters/update/<?= $o['id'] ?>">

    <div class="row g-4">

        <!-- Left Column -->
        <div class="col-lg-8">

            <!-- Candidate Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-user me-2"></i>Candidate Details</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Candidate Name <span class="text-danger">*</span></label>
                            <input type="text" name="candidate_name" class="form-control" required value="<?= htmlspecialchars($o['candidate_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Designation</label>
                            <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($o['designation']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department</label>
                            <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($o['department']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nationality</label>
                            <input type="text" name="nationality" class="form-control" value="<?= htmlspecialchars($o['nationality']) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offer Dates -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-calendar me-2"></i>Dates</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Offer Date</label>
                            <input type="date" name="offer_date" class="form-control" value="<?= htmlspecialchars($o['offer_date']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expected Joining Date</label>
                            <input type="date" name="joining_date" class="form-control" value="<?= htmlspecialchars($o['joining_date'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Package -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-money-bill-wave me-2"></i>Salary Package</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Basic Salary</label>
                            <div class="input-group">
                                <span class="input-group-text">AED</span>
                                <input type="number" name="basic_salary" class="form-control salary-input" step="0.01" min="0" value="<?= htmlspecialchars($o['basic_salary']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Housing Allowance</label>
                            <div class="input-group">
                                <span class="input-group-text">AED</span>
                                <input type="number" name="housing_allowance" class="form-control salary-input" step="0.01" min="0" value="<?= htmlspecialchars($o['housing_allowance']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Transport Allowance</label>
                            <div class="input-group">
                                <span class="input-group-text">AED</span>
                                <input type="number" name="transport_allowance" class="form-control salary-input" step="0.01" min="0" value="<?= htmlspecialchars($o['transport_allowance']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Other Allowance</label>
                            <div class="input-group">
                                <span class="input-group-text">AED</span>
                                <input type="number" name="other_allowance" class="form-control salary-input" step="0.01" min="0" value="<?= htmlspecialchars($o['other_allowance']) ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background:#f0f7ff;border:1px solid #cde;">
                                <span class="fw-semibold text-muted">Total Monthly Package: </span>
                                <span id="total-package" class="fw-bold text-primary fs-5">AED 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-file-contract me-2"></i>Employment Terms</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Probation Period</label>
                            <input type="text" name="probation_period" class="form-control" value="<?= htmlspecialchars($o['probation_period']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Working Hours</label>
                            <input type="text" name="working_hours" class="form-control" value="<?= htmlspecialchars($o['working_hours']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Annual Leave</label>
                            <input type="text" name="annual_leave" class="form-control" value="<?= htmlspecialchars($o['annual_leave']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Notice Period</label>
                            <input type="text" name="notice_period" class="form-control" value="<?= htmlspecialchars($o['notice_period']) ?>">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-4">

            <!-- Offer Reference -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-hashtag me-2"></i>Reference</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <label class="form-label fw-semibold">Offer Letter No.</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($o['offer_no']) ?>" readonly>
                </div>
            </div>

            <!-- Issued By -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-signature me-2"></i>Authorized By</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="issued_by" class="form-control" value="<?= htmlspecialchars($o['issued_by']) ?>">
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Title / Designation</label>
                        <input type="text" name="issued_by_title" class="form-control" value="<?= htmlspecialchars($o['issued_by_title']) ?>">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <textarea name="notes" class="form-control" rows="4"><?= htmlspecialchars($o['notes']) ?></textarea>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Save Changes</button>
                <a href="<?= BASE_URL ?>offerletters/show/<?= $o['id'] ?>" class="btn btn-light">Cancel</a>
            </div>

        </div>
    </div>
</form>

<script>
(function () {
    const inputs = document.querySelectorAll('.salary-input');
    const total  = document.getElementById('total-package');

    function recalc() {
        let sum = 0;
        inputs.forEach(i => sum += parseFloat(i.value) || 0);
        total.textContent = 'AED ' + sum.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    inputs.forEach(i => i.addEventListener('input', recalc));
    recalc();
})();
</script>
