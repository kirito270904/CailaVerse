<div class="fb-auth-container position-relative">
    <!-- Top-Right Theme Toggle -->
    <div class="position-absolute top-0 end-0 p-3">
        <button type="button" class="btn btn-soft btn-sm rounded-pill theme-toggle" title="Toggle Theme" aria-label="Toggle Theme">
            <i class="bi bi-moon-stars"></i>
        </button>
    </div>

    <div class="fb-auth-content">
        <!-- Left Brand Section (Facebook Style) -->
        <div class="fb-brand-col fade-up">
            <div class="d-flex align-items-center gap-3 mb-2 justify-content-center justify-content-lg-start">
                <img src="assets/img/logo.svg" alt="CailaVerse" class="fb-brand-logo" width="62" height="62">
                <h1 class="fb-brand-name mb-0">cailaverse</h1>
            </div>
            <h2 class="fb-brand-slogan">
                CailaVerse helps you connect and share with the people in your life.
            </h2>
        </div>

        <!-- Right Login Card Section (Facebook Style) -->
        <div class="fb-form-col fade-up">
            <div class="fb-login-box">
                <form method="post" action="<?= e(url('auth', 'login')) ?>" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <input type="text" id="username" name="username" class="form-control fb-input" placeholder="Username" value="<?= e($username) ?>" autocomplete="username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" id="password" name="password" class="form-control fb-input pe-5" placeholder="Password" autocomplete="current-password" required>
                        <button type="button" class="eye-btn" data-toggle-password="#password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                    <button class="btn btn-caila btn-fb-login w-100 shadow-sm" type="submit">
                        Log In
                    </button>
                    <div class="text-center my-3">
                        <a href="<?= e(url('auth', 'login')) ?>" class="small text-decoration-none fb-forgot-link">Forgotten account?</a>
                    </div>
                    <hr class="fb-divider my-3">
                    <div class="text-center">
                        <a href="<?= e(url('auth', 'register')) ?>" class="btn btn-fb-register">
                            Create new account
                        </a>
                    </div>
                </form>
            </div>
            <p class="fb-bottom-tagline text-center mt-4">
                <a href="<?= e(url('auth', 'register')) ?>" class="fw-bold text-reset text-decoration-none">Create a Profile</a> for your student community on CailaVerse.
            </p>
        </div>
    </div>

    <!-- Facebook Style Auth Footer -->
    <footer class="fb-auth-footer">
        <div class="fb-footer-inner">
            <div class="fb-languages mb-2">
                <span>English (US)</span> &middot;
                <span>Filipino</span> &middot;
                <span>Bisaya</span> &middot;
                <span>Cebuano</span> &middot;
                <span>Español</span> &middot;
                <span>日本語</span>
            </div>
            <hr class="my-2 opacity-25">
            <div class="fb-links small text-muted">
                <a href="<?= e(url('auth', 'register')) ?>">Sign Up</a> &middot;
                <a href="<?= e(url('auth', 'login')) ?>">Log In</a> &middot;
                <span>CailaVerse &copy; <?= date('Y') ?></span> &middot;
                <span>SMCC Web Systems &amp; Technologies</span>
            </div>
        </div>
    </footer>
</div>
