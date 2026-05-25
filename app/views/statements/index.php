<style>
.stmt-pick-card { border-radius:16px; border:none; transition: box-shadow 0.2s, transform 0.2s; }
.stmt-pick-card:hover { box-shadow: 0 10px 32px rgba(0,0,0,0.10) !important; transform: translateY(-2px); }
.stmt-pick-icon { width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
</style>

<div class="mb-4">
    <h4 class="fw-bold mb-1">Statements</h4>
    <p class="text-muted small">Generate a Sales Statement by salesman, or a Client Statement by client.</p>
</div>

<div class="row g-4">

    <!-- ===== SALES STATEMENT ===== -->
    <div class="col-md-6">
        <div class="card stmt-pick-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="stmt-pick-icon" style="background:#eef2ff;">
                        <i class="fas fa-user-tie" style="color:#6366f1;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Sales Statement</h5>
                        <small class="text-muted">Invoices grouped by salesman</small>
                    </div>
                </div>

                <form method="POST" action="<?= BASE_URL ?>statements/salesman">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Salesman</label>
                        <select name="salesman_id" class="form-select" required>
                            <option value="">— Choose a salesman —</option>
                            <?php foreach ($salesmen as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= $s['role'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-file-alt me-2"></i>Generate Sales Statement
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Shows all leads &amp; invoices assigned to the chosen salesman with SL#, invoice number, amount, and payment status.
                </p>
            </div>
        </div>
    </div>

    <!-- ===== CLIENT STATEMENT ===== -->
    <div class="col-md-6">
        <div class="card stmt-pick-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="stmt-pick-icon" style="background:#f0fdf4;">
                        <i class="fas fa-building" style="color:#10b981;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Client Statement</h5>
                        <small class="text-muted">Full invoice history for a client</small>
                    </div>
                </div>

                <form method="POST" action="<?= BASE_URL ?>statements/client">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Client</label>
                        <select name="client_id" class="form-select" required id="clientSelect">
                            <option value="">— Choose a client —</option>
                            <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['company_name'] ?: $c['lead_name']) ?>
                                <?php if ($c['company_name'] && $c['lead_name'] !== $c['company_name']): ?> — <?= htmlspecialchars($c['lead_name']) ?><?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-file-alt me-2"></i>Generate Client Statement
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Shows complete invoice history for the selected client with invoice number, date, amount, and payment status.
                </p>
            </div>
        </div>
    </div>

    <!-- ===== PAYMENT VOUCHER STATEMENT ===== -->
    <div class="col-12">
        <div class="card stmt-pick-card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="stmt-pick-icon" style="background:#fff7ed;">
                        <i class="fas fa-file-invoice-dollar" style="color:#e8602c;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Payment Voucher Statement</h5>
                        <small class="text-muted">All payment vouchers issued for a client</small>
                    </div>
                </div>

                <form method="POST" action="<?= BASE_URL ?>statements/voucher">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Select Client</label>
                            <select name="client_id" class="form-select" required>
                                <option value="">— Choose a client —</option>
                                <?php foreach ($clients as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['company_name'] ?: $c['lead_name']) ?>
                                    <?php if ($c['company_name'] && $c['lead_name'] !== $c['company_name']): ?> — <?= htmlspecialchars($c['lead_name']) ?><?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn w-100" style="background:#e8602c; color:#fff;">
                                <i class="fas fa-file-alt me-2"></i>Generate Voucher Statement
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-3">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Shows all payment vouchers issued for the selected client with voucher number, date, mode, invoices covered, and total paid.
                </p>
            </div>
        </div>
    </div>

</div>
