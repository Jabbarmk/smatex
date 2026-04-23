<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="card border-0 shadow-lg">
      <div class="card-header bg-white border-bottom-0 pb-0">
        <h5 class="fw-bold mt-2"><i class="fas fa-user-plus me-2 text-primary"></i>Add New Employee</h5>
      </div>
      <div class="card-body">
        <form action="<?= BASE_URL ?>employees/store" method="POST">

          <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary">Basic Information</h6>
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Employee No</label>
              <input type="text" name="employee_no" class="form-control bg-light" value="<?= $employee_no ?>" readonly>
            </div>
            <div class="col-md-5">
              <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Join Date</label>
              <input type="date" name="join_date" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Designation</label>
              <input type="text" name="designation" class="form-control" placeholder="e.g. Software Engineer">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Department</label>
              <input type="text" name="department" class="form-control" placeholder="e.g. Technology">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Nationality</label>
              <input type="text" name="nationality" class="form-control" placeholder="e.g. Indian">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Mobile</label>
              <input type="text" name="mobile" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>

          <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4 text-primary">Document Information</h6>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Passport No</label>
              <input type="text" name="passport_no" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Visa / UID No</label>
              <input type="text" name="visa_uid" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Emirates ID</label>
              <input type="text" name="emirates_id" class="form-control" placeholder="784-XXXX-XXXXXXX-X">
            </div>
          </div>

          <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4 text-primary">Salary Details</h6>
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Basic Salary</label>
              <input type="number" name="basic_salary" class="form-control" value="0" min="0" step="0.01" oninput="calcGross()">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Housing Allowance</label>
              <input type="number" name="housing_allowance" class="form-control" value="0" min="0" step="0.01" oninput="calcGross()">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Transport Allowance</label>
              <input type="number" name="transport_allowance" class="form-control" value="0" min="0" step="0.01" oninput="calcGross()">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Other Allowance</label>
              <input type="number" name="other_allowance" class="form-control" value="0" min="0" step="0.01" oninput="calcGross()">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Total Gross Salary</label>
              <input type="text" id="gross_display" class="form-control bg-light fw-bold text-success" readonly value="0.00">
            </div>
          </div>

          <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4 text-primary">Bank Details</h6>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Bank Name</label>
              <input type="text" name="bank_name" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Account Number</label>
              <input type="text" name="bank_account" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">IBAN</label>
              <input type="text" name="iban" class="form-control">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Notes</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
          </div>

          <div class="text-end mt-4">
            <a href="<?= BASE_URL ?>employees" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Employee</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function calcGross() {
    const basic     = parseFloat(document.querySelector('[name="basic_salary"]').value) || 0;
    const housing   = parseFloat(document.querySelector('[name="housing_allowance"]').value) || 0;
    const transport = parseFloat(document.querySelector('[name="transport_allowance"]').value) || 0;
    const other     = parseFloat(document.querySelector('[name="other_allowance"]').value) || 0;
    document.getElementById('gross_display').value = (basic + housing + transport + other).toFixed(2);
}
calcGross();
</script>
