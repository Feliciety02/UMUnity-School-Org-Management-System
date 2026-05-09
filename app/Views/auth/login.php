<div class="body">
    <button class="back-btn" onclick="window.location.href='<?= url('/') ?>'">
        <i class="fa-solid fa-arrow-left"></i>
    </button>

    <div class="login-wrapper">
        <div class="login-left">
            <div class="overlay"></div>
            <div class="left-content">
                <img src="<?= asset('assets/images/logo1.png') ?>" alt="UMUnity Logo">
                <h2>Welcome Back to UMUnity</h2>
                <p>Sign in through the new application layer and continue to your role-based dashboard.</p>
            </div>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h2>Sign In</h2>
                <p>Access your account</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars((string)$error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars((string)$success) ?></div>
            <?php endif; ?>

            <form action="<?= url('/login') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3 password-wrapper">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <p class="mt-4 mb-0 text-center text-muted">No account yet? <a href="<?= url('/register') ?>">Create one</a></p>
        </div>
    </div>
</div>
