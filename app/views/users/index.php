<div class="row mb-4">
    <div class="col-md-12 text-end">
        <a href="<?= BASE_URL ?>users/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New User
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 py-3">
        <h5 class="mb-0 fw-bold">User Management</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php 
                            $roleClass = match($user['role']) {
                                'Super Admin' => 'bg-primary text-white',
                                'Admin' => 'bg-info text-white',
                                'Sales' => 'bg-secondary text-white',
                                default => 'bg-light text-dark'
                            };
                            ?>
                            <span class="badge <?= $roleClass ?>"><?= $user['role'] ?></span>
                        </td>
                        <td>
                            <?php if ($user['status'] == 'Active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-light text-primary"><i class="fas fa-edit"></i></a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="<?= BASE_URL ?>users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Delete this user? This action cannot be undone.')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
