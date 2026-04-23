<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>public/assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-container">
    <div class="card login-card shadow-lg border-0">
        <div class="card-body">
            <h3 class="text-center mb-4 fw-bold" style="color: var(--primary-color);">Welcome Back</h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form action="<?= BASE_URL ?>auth/authenticate" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" required placeholder="admin@smatex.com">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required placeholder="password">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Default: admin@smatex.com / password</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>
