<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Create New Lead</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>leads/store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lead Name</label>
                            <input type="text" name="lead_name" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emirates</label>
                            <select name="emirates" class="form-select bg-light border-0">
                                <option>Dubai</option>
                                <option>Abu Dhabi</option>
                                <option>Sharjah</option>
                                <option>Ajman</option>
                                <option>Fujairah</option>
                                <option>RAK</option>
                                <option>UAQ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assign to Sales Manager</label>
                            <select name="sales_manager_id" class="form-select bg-light border-0">
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>"><?= $user['name'] ?> (<?= $user['role'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Value</label>
                            <input type="number" name="expected_value" class="form-control bg-light border-0" placeholder="0.00">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control bg-light border-0" rows="3"></textarea>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <a href="<?= BASE_URL ?>leads" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Create Lead</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
