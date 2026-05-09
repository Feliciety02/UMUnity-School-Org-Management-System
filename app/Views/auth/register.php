<div class="body">
    <button class="back-btn" onclick="window.location.href='<?= url('/') ?>'">
        <i class="fa-solid fa-arrow-left"></i>
    </button>

    <div class="login-wrapper">
        <div class="login-left">
            <div class="overlay"></div>
            <div class="left-content">
                <img src="<?= asset('assets/images/logo1.png') ?>" alt="UMUnity Logo">
                <h2>Create Your UMUnity Account</h2>
                <p>Start with a student or leader account and continue inside the new framework structure.</p>
            </div>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h2>Register</h2>
                <p>Create your account</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars((string)$error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars((string)$success) ?></div>
            <?php endif; ?>

            <form action="<?= url('/register') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3 password-wrapper">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="student">Student</option>
                        <option value="leader">Organization Leader</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>

            <p class="mt-4 mb-0 text-center text-muted">Already registered? <a href="<?= url('/login') ?>">Sign in</a></p>
        </div>
    </div>
</div>
