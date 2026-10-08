<div class="fb-auth-container position-relative justify-content-center py-5">
    <!-- Top-Right Theme Toggle -->
    <div class="position-absolute top-0 end-0 p-3">
        <button type="button" class="btn btn-soft btn-sm rounded-pill theme-toggle" id="themeToggle" title="Toggle Theme" aria-label="Toggle Theme">
            <i class="bi bi-moon-stars"></i>
        </button>
    </div>

    <div class="fb-register-box card card-caila fade-up">
        <div class="text-center mb-3">
            <div class="d-inline-flex align-items-center gap-2 mb-2">
                <img src="assets/img/logo.svg" alt="OmniSphere" width="48" height="48">
                <span class="fb-brand-name fs-2">omnisphere</span>
            </div>
            <h2 class="fw-bold mb-1">Create a new account</h2>
            <p class="text-muted small mb-0">It's quick and easy.</p>
        </div>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger py-2 px-3 small mb-3 rounded-3">
                <?php foreach ($errors as $err): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                        <span><?= e($err) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <hr class="fb-divider my-3">
        <form method="post" action="<?= e(url('auth', 'register')) ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <div class="mb-3">
                <input type="text" id="full_name" name="full_name" class="form-control fb-input" placeholder="Full name" maxlength="100" value="<?= e($old['full_name'] ?? '') ?>" required>
                <div class="invalid-feedback">Full name is required.</div>
            </div>
            <div class="mb-3">
                <input type="text" id="reg_username" name="username" class="form-control fb-input" placeholder="Username (3-20 letters/numbers)" pattern="[A-Za-z0-9_]{3,20}" value="<?= e($old['username'] ?? '') ?>" autocomplete="username" required>
                <div class="invalid-feedback">3–20 characters: letters, numbers, underscore.</div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-sm-6">
                    <div class="position-relative">
                        <input type="password" id="reg_password" name="password" class="form-control fb-input pe-5" placeholder="New password" minlength="6" autocomplete="new-password" required>
                        <button type="button" class="eye-btn" data-toggle-password="#reg_password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                    </div>
                    <div class="invalid-feedback">At least 6 characters.</div>
                </div>
                <div class="col-sm-6">
                    <input type="password" id="reg_confirm" name="confirm" class="form-control fb-input" placeholder="Confirm password" autocomplete="new-password" required>
                    <div class="invalid-feedback">Passwords must match.</div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small text-muted mb-1">Profile Picture (optional)</label>
                <input type="file" name="profile_image" class="form-control fb-input py-2" accept="image/*" data-preview="#reg-preview">
                <div id="reg-preview" class="preview-box preview-round d-none mt-2"></div>
            </div>
            <p class="text-muted text-center" style="font-size: 0.78rem;">
                By clicking Sign Up, you agree to connect on OmniSphere for SMCC Web Systems and Technologies.
            </p>
            <div class="text-center my-3">
                <button class="btn btn-fb-register px-5 w-100" type="submit">
                    Sign Up
                </button>
            </div>
            <hr class="fb-divider my-3">
            <div class="text-center">
                <a href="<?= e(url('auth', 'login')) ?>" class="text-decoration-none fw-bold small fb-forgot-link">Already have an account? Log In</a>
            </div>
        </form>
    </div>

    <!-- Facebook Style Auth Footer -->
    <footer class="fb-auth-footer text-center mt-4">
        <div class="fb-footer-inner">
            <div class="fb-links small text-muted justify-content-center">
                <a href="<?= e(url('auth', 'register')) ?>">Sign Up</a> &middot;
                <a href="<?= e(url('auth', 'login')) ?>">Log In</a> &middot;
                <span>OmniSphere &copy; <?= date('Y') ?></span> &middot;
                <span>SMCC Web Systems &amp; Technologies</span>
            </div>
        </div>
    </footer>
</div>
