<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $_GET['success'] === 'added' ? 'Employee added successfully.' : 'Employee updated successfully.' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Manage your team members and their details.</p>
    </div>
    <a href="<?= BASE_URL ?>employees/create" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add Employee
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
        <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2 text-primary"></i>Employee Master</h5>
        <small class="text-muted"><?= count($employees) ?> employees</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Emp No</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Mobile</th>
                        <th class="text-end">Basic Salary</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-5">No employees found. <a href="<?= BASE_URL ?>employees/create">Add one?</a></td></tr>
                    <?php else: ?>
                    <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($emp['employee_no']) ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;border-radius:50%;background:#667eea22;display:flex;align-items:center;justify-content:center;font-weight:700;color:#667eea;flex-shrink:0;">
                                    <?= strtoupper(substr($emp['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($emp['full_name']) ?></div>
                                    <?php if ($emp['email']): ?><small class="text-muted"><?= htmlspecialchars($emp['email']) ?></small><?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($emp['designation']) ?></td>
                        <td><?= htmlspecialchars($emp['department']) ?></td>
                        <td><?= htmlspecialchars($emp['mobile']) ?></td>
                        <td class="text-end fw-semibold"><?= formatMoney($emp['basic_salary']) ?></td>
                        <td class="text-center">
                            <span class="badge <?= $emp['status'] === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $emp['status'] ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>salary/create?emp=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-success" title="New Salary Voucher"><i class="fas fa-file-invoice-dollar"></i></a>
                            <a href="<?= BASE_URL ?>offerletters/create?emp=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-primary" title="Create Offer Letter"><i class="fas fa-file-signature"></i></a>
                            <a href="<?= BASE_URL ?>employees/edit/<?= $emp['id'] ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="<?= BASE_URL ?>employees/delete/<?= $emp['id'] ?>" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this employee?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
