<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Create New User</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>users/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control bg-light border-0">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select bg-light border-0">
                                <option value="Sales">Sales</option>
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select bg-light border-0">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control bg-light border-0" required minlength="6">
                    </div>
                    <div class="text-end">
                        <a href="<?= BASE_URL ?>users" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
