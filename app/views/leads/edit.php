<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Edit Lead</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>leads/update/<?= $lead['id'] ?>" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lead Name</label>
                            <input type="text" name="lead_name" class="form-control bg-light border-0" value="<?= htmlspecialchars($lead['lead_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control bg-light border-0" value="<?= htmlspecialchars($lead['company_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control bg-light border-0" value="<?= htmlspecialchars($lead['phone']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control bg-light border-0" value="<?= htmlspecialchars($lead['email']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emirates</label>
                            <select name="emirates" class="form-select bg-light border-0">
                                <?php 
                                $emirates = ['Dubai','Abu Dhabi','Sharjah','Ajman','Fujairah','RAK','UAQ'];
                                foreach($emirates as $em) {
                                    $selected = ($lead['emirates'] == $em) ? 'selected' : '';
                                    echo "<option value='$em' $selected>$em</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select bg-light border-0">
                                <?php 
                                $statuses = ['New','Contacted','Qualified','Proposal Sent','Won','Lost'];
                                foreach($statuses as $st) {
                                    $selected = ($lead['status'] == $st) ? 'selected' : '';
                                    echo "<option value='$st' $selected>$st</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assign to Sales Manager</label>
                            <select name="sales_manager_id" class="form-select bg-light border-0">
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= ($user['id'] == $lead['sales_manager_id']) ? 'selected' : '' ?>><?= $user['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Value</label>
                            <input type="number" name="expected_value" class="form-control bg-light border-0" value="<?= $lead['expected_value'] ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control bg-light border-0" rows="3"><?= htmlspecialchars($lead['notes']) ?></textarea>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <a href="<?= BASE_URL ?>leads" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update Lead</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
