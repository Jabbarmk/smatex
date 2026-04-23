<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Add New Expense</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>expenses/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Expense Title</label>
                        <input type="text" name="title" class="form-control bg-light border-0" required placeholder="e.g. Office Supplies">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="expense_date" class="form-control bg-light border-0" required value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select bg-light border-0">
                                <option>Office Rent</option>
                                <option>Utilities</option>
                                <option>Salaries</option>
                                <option>Marketing</option>
                                <option>Travel</option>
                                <option>Software</option>
                                <option>Miscellaneous</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Mode</label>
                            <select name="payment_mode" class="form-select bg-light border-0">
                                <option>Cash</option>
                                <option>Bank Transfer</option>
                                <option>Cheque</option>
                                <option>Credit Card</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="3"></textarea>
                    </div>
                    <div class="text-end">
                        <a href="<?= BASE_URL ?>expenses" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
