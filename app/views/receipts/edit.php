<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Edit Payment</h5>
                <p class="text-muted small">Update Receipt: <?= $receipt['receipt_no'] ?> for Invoice: <?= $invoice['invoice_no'] ?></p>
            </div>
            <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Invoice Total:</strong> <?= formatMoney($invoice['grand_total']) ?> | 
                        <strong>Max Amount:</strong> <?= formatMoney($balance_due) ?>
                    </div>

                    <form action="<?= BASE_URL ?>receipts/update/<?= $receipt['id'] ?>" method="POST">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Receipt No</label>
                            <input type="text" class="form-control fw-bold bg-light" value="<?= $receipt['receipt_no'] ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= $receipt['payment_date'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Amount Received</label>
                        <div class="input-group">
                            <span class="input-group-text"><?= $currency_symbol ?></span>
                            <input type="number" name="amount_paid" class="form-control fs-4 fw-bold text-success" step="0.01" value="<?= $receipt['amount_paid'] ?>" max="<?= $balance_due ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Mode</label>
                            <select name="payment_mode" class="form-select" required>
                                <option value="Bank Transfer" <?= ($receipt['payment_mode'] == 'Bank Transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                                <option value="Cheque" <?= ($receipt['payment_mode'] == 'Cheque') ? 'selected' : '' ?>>Cheque</option>
                                <option value="Cash" <?= ($receipt['payment_mode'] == 'Cash') ? 'selected' : '' ?>>Cash</option>
                                <option value="Credit Card" <?= ($receipt['payment_mode'] == 'Credit Card') ? 'selected' : '' ?>>Credit Card</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Reference / Cheque No.</label>
                            <input type="text" name="reference_number" class="form-control" value="<?= $receipt['reference_number'] ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"><?= $receipt['notes'] ?></textarea>
                    </div>

                    <div class="text-end">
                        <a href="<?= BASE_URL ?>receipts" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Update Receipt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
