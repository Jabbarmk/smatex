<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card border-0 shadow-lg">
      <div class="card-header bg-white border-bottom-0 pb-0">
        <h5 class="fw-bold mt-2"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Create Salary Payment Voucher</h5>
      </div>
      <div class="card-body">
        <form action="<?= BASE_URL ?>salary/store" method="POST" id="salaryForm">

          <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary">Voucher Details</h6>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Voucher No</label>
              <input type="text" name="voucher_no" class="form-control bg-light fw-bold" value="<?= $voucher_no ?>" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
              <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Payment Mode</label>
              <select name="payment_mode" class="form-select">
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Cash">Cash</option>
                <option value="Cheque">Cheque</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Salary Month <span class="text-danger">*</span></label>
              <select name="payment_month" class="form-select" required>
                <?php foreach ($months as $m): ?>
                  <option value="<?= $m ?>" <?= (date('F') === $m) ? 'selected' : '' ?>><?= $m ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
              <input type="number" name="payment_year" class="form-control" value="<?= date('Y') ?>" min="2000" max="2100" required>
            </div>
          </div>

          <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4 text-primary">Employee</h6>
          <div class="mb-3">
            <label class="form-label fw-semibold">Select Employee <span class="text-danger">*</span></label>
            <select name="employee_id" class="form-select" id="empSelect" required onchange="loadEmployee(this.value)">
              <option value="">-- Select Employee --</option>
              <?php foreach ($employees as $emp): ?>
                <option value="<?= $emp['id'] ?>"
                  data-basic="<?= $emp['basic_salary'] ?>"
                  data-housing="<?= $emp['housing_allowance'] ?>"
                  data-transport="<?= $emp['transport_allowance'] ?>"
                  data-other="<?= $emp['other_allowance'] ?>"
                  <?= isset($_GET['emp']) && $_GET['emp'] == $emp['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($emp['employee_no'] . ' — ' . $emp['full_name']) ?>
                  (<?= htmlspecialchars($emp['designation']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4 text-primary">Salary Breakdown</h6>
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Basic Salary</label>
              <input type="number" name="basic_salary" id="f_basic" class="form-control" value="0" min="0" step="0.01" oninput="calcNet()">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Housing Allowance</label>
              <input type="number" name="housing_allowance" id="f_housing" class="form-control" value="0" min="0" step="0.01" oninput="calcNet()">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Transport Allowance</label>
              <input type="number" name="transport_allowance" id="f_transport" class="form-control" value="0" min="0" step="0.01" oninput="calcNet()">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Other Allowance</label>
              <input type="number" name="other_allowance" id="f_other" class="form-control" value="0" min="0" step="0.01" oninput="calcNet()">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Gross Salary</label>
              <input type="text" id="f_gross" class="form-control bg-light fw-bold" readonly value="0.00">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Deductions</label>
              <input type="number" name="deductions" id="f_deductions" class="form-control" value="0" min="0" step="0.01" oninput="calcNet()">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Deduction Reason</label>
              <input type="text" name="deduction_reason" class="form-control" placeholder="Leave without pay, advance, etc.">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Net Salary Payable</label>
              <input type="text" id="f_net" class="form-control fw-bold fs-5 text-success bg-light" readonly value="0.00">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Notes</label>
            <textarea name="notes" class="form-control" rows="2" placeholder="Optional remarks..."></textarea>
          </div>

          <div class="text-end mt-4">
            <a href="<?= BASE_URL ?>salary" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-paper-plane me-1"></i> Save & View Voucher</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function loadEmployee(id) {
    const sel = document.getElementById('empSelect');
    const opt = sel.querySelector('[value="' + id + '"]');
    if (!opt) return;
    document.getElementById('f_basic').value     = opt.dataset.basic || 0;
    document.getElementById('f_housing').value   = opt.dataset.housing || 0;
    document.getElementById('f_transport').value = opt.dataset.transport || 0;
    document.getElementById('f_other').value     = opt.dataset.other || 0;
    calcNet();
}

function calcNet() {
    const basic     = parseFloat(document.getElementById('f_basic').value) || 0;
    const housing   = parseFloat(document.getElementById('f_housing').value) || 0;
    const transport = parseFloat(document.getElementById('f_transport').value) || 0;
    const other     = parseFloat(document.getElementById('f_other').value) || 0;
    const deductions = parseFloat(document.getElementById('f_deductions').value) || 0;
    const gross = basic + housing + transport + other;
    const net   = gross - deductions;
    document.getElementById('f_gross').value = gross.toFixed(2);
    document.getElementById('f_net').value   = net.toFixed(2);
}

// Auto-load if employee pre-selected via URL param
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('empSelect');
    if (sel.value) loadEmployee(sel.value);
});
</script>
