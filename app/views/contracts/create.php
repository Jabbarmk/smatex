<div class="mb-3">
    <a href="<?= BASE_URL ?>contracts" class="btn btn-light btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Contracts</a>
</div>

<form method="POST" action="<?= BASE_URL ?>contracts/store">

    <!-- Basic Info -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold py-3"><i class="fas fa-file-contract me-2 text-primary"></i>Contract Details</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Contract Number</label>
                    <input type="text" name="contract_no" class="form-control" value="<?= htmlspecialchars($contract_no) ?>" readonly>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Title / Subject <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Software Development Agreement" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Contract Type</label>
                    <select name="contract_type" class="form-select">
                        <option>Service Agreement</option>
                        <option>Sales Agreement</option>
                        <option>Employment Contract</option>
                        <option>Non-Disclosure Agreement (NDA)</option>
                        <option>Partnership Agreement</option>
                        <option>Lease Agreement</option>
                        <option>Consultancy Agreement</option>
                        <option>Maintenance Agreement</option>
                        <option>Supply Agreement</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Contract Value</label>
                    <input type="number" name="value" class="form-control" placeholder="0.00" min="0" step="0.01">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="Draft" selected>Draft</option>
                        <option value="Active">Active</option>
                        <option value="Expired">Expired</option>
                        <option value="Terminated">Terminated</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Parties -->
    <div class="row g-4 mb-4">
        <!-- First Party -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3"><i class="fas fa-building me-2 text-primary"></i>First Party</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Company / Person Name</label>
                        <input type="text" name="first_party_name" class="form-control" placeholder="Company or individual name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="first_party_address" class="form-control" rows="2" placeholder="Full address"></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="first_party_phone" class="form-control" placeholder="+971 ...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="first_party_email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Representative</label>
                            <input type="text" name="first_party_representative" class="form-control" placeholder="Full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Designation</label>
                            <input type="text" name="first_party_designation" class="form-control" placeholder="e.g. CEO">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Second Party -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3"><i class="fas fa-user-tie me-2 text-success"></i>Second Party</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Company / Person Name</label>
                        <input type="text" name="second_party_name" class="form-control" placeholder="Company or individual name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="second_party_address" class="form-control" rows="2" placeholder="Full address"></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="second_party_phone" class="form-control" placeholder="+971 ...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="second_party_email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Representative</label>
                            <input type="text" name="second_party_representative" class="form-control" placeholder="Full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Designation</label>
                            <input type="text" name="second_party_designation" class="form-control" placeholder="e.g. Manager">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contents -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold py-3"><i class="fas fa-align-left me-2 text-primary"></i>Contract Contents</div>
        <div class="card-body">
            <label class="form-label fw-semibold">Contract Body</label>
            <textarea name="contents" id="contents" rows="14"></textarea>
            <div class="mt-3">
                <label class="form-label fw-semibold">Terms &amp; Conditions</label>
                <textarea name="terms_conditions" id="terms_conditions" rows="5"></textarea>
            </div>
            <div class="mt-3">
                <label class="form-label fw-semibold">Internal Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes (not shown on printed contract)"></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Contract</button>
        <a href="<?= BASE_URL ?>contracts" class="btn btn-light px-4">Cancel</a>
    </div>

</form>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
const editorConfig = {
    toolbar: [
        { name: 'styles',    items: ['Styles', 'Format', 'FontSize'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'clipboard', items: ['PasteFromWord', 'PasteText', '-', 'Undo', 'Redo'] },
        { name: 'links',     items: ['Link', 'Unlink'] },
        { name: 'insert',    items: ['Table', 'HorizontalRule', 'SpecialChar'] },
        { name: 'tools',     items: ['Maximize'] }
    ],
    height: 420,
    removePlugins: 'elementspath',
    resize_enabled: true,
    extraAllowedContent: true,
    pasteFromWordRemoveFontStyles: false,
    pasteFromWordRemoveStyles: false,
};
CKEDITOR.replace('contents', editorConfig);
CKEDITOR.replace('terms_conditions', { ...editorConfig, height: 220 });
</script>
