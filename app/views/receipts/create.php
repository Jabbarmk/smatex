<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h5 class="fw-bold mt-2">Record Payment</h5>
            </div>
            <div class="card-body">
                
                <?php if (!$invoice): ?>
                <div class="mb-4">
                    <label class="form-label text-muted">Select Invoice to Pay</label>
                    
                    <?php if(empty($invoices)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i> No unpaid invoices found. 
                            <a href="<?= BASE_URL ?>invoices/create">Create one?</a>
                        </div>
                    <?php else: ?>
                        <div class="input-group">
                            <select class="form-select fs-5" id="invoiceSelect" onchange="loadInvoice(this.value)">
                                <option value="">Choose an invoice...</option>
                                <?php foreach($invoices as $inv): ?>
                                <option value="<?= $inv['id'] ?>">
                                    #<?= $inv['invoice_no'] ?> - <?= $inv['lead_name'] ?? $inv['client_details'] ?> (<?= formatMoney($inv['grand_total']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-primary" type="button" onclick="triggerLoad()">Load</button>
                        </div>
                        <div class="form-text text-muted mt-2">Select an invoice to proceed with payment recording.</div>
                    <?php endif; ?>
                </div>
                
                <script>
                function loadInvoice(val) {
                    if(val) {
                        console.log("Redirecting to: " + '<?= BASE_URL ?>receipts/create/' + val);
                        window.location.href = '<?= BASE_URL ?>receipts/create/' + val;
                    }
                }
                function triggerLoad() {
                    var val = document.getElementById('invoiceSelect').value;
                    if(val) {
                        loadInvoice(val);
                    } else {
                        alert("Please select an invoice first.");
                    }
                }
                </script>
                </div>
                <?php else: ?>

                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Invoice:</strong> <?= $invoice['invoice_no'] ?><br>
                        <strong>Total:</strong> <?= formatMoney($invoice['grand_total']) ?>
                    </div>
                    <div class="text-end">
                        <strong>Balance Due:</strong><br>
                        <span class="fs-5"><?= formatMoney($balance_due) ?></span>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>receipts/store" method="POST">
                    <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Receipt No</label>
                            <input type="text" name="receipt_no" class="form-control fw-bold bg-light" value="<?= $receipt_no ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Amount Received</label>
                        <div class="input-group">
                            <span class="input-group-text"><?= $currency_symbol ?></span>
                            <input type="number" name="amount_paid" class="form-control fs-4 fw-bold text-success" step="0.01" max="<?= $balance_due ?>" required>
                        </div>
                        <div class="form-text">Cannot exceed balance due (<?= formatMoney($balance_due) ?>).</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Payment Mode</label>
                            <select name="payment_mode" class="form-select" required>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Cash">Cash</option>
                                <option value="Credit Card">Credit Card</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Reference / Cheque No.</label>
                            <input type="text" name="reference_number" class="form-control" placeholder="Optional">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="text-end">
                        <a href="<?= BASE_URL ?>receipts/create" class="btn btn-light me-2">Change Invoice</a>
                        <button type="submit" class="btn btn-success px-4"><i class="fas fa-check"></i> Record Payment</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
