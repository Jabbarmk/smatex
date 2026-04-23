<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Edit Expense</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>expenses/update/<?= $expense['id'] ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Expense Title</label>
                        <input type="text" name="title" class="form-control bg-light border-0" required value="<?= htmlspecialchars($expense['title']) ?>">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control bg-light border-0" required value="<?= $expense['amount'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="expense_date" class="form-control bg-light border-0" required value="<?= $expense['expense_date'] ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select bg-light border-0">
                                <?php 
                                $cats = ['Office Rent', 'Utilities', 'Salaries', 'Marketing', 'Travel', 'Software', 'Miscellaneous'];
                                foreach($cats as $cat) {
                                    $sel = ($expense['category'] == $cat) ? 'selected' : '';
                                    echo "<option $sel>$cat</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Mode</label>
                            <select name="payment_mode" class="form-select bg-light border-0">
                                <?php 
                                $modes = ['Cash', 'Bank Transfer', 'Cheque', 'Credit Card'];
                                foreach($modes as $mode) {
                                    $sel = ($expense['payment_mode'] == $mode) ? 'selected' : '';
                                    echo "<option $sel>$mode</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="3"><?= htmlspecialchars($expense['description']) ?></textarea>
                    </div>
                    <div class="text-end">
                        <a href="<?= BASE_URL ?>expenses" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
