<div class="fb-auth-container position-relative">
    <!-- Top-Right Theme Toggle -->
    <div class="position-absolute top-0 end-0 p-3">
        <button type="button" class="btn btn-soft btn-sm rounded-pill theme-toggle" id="themeToggle" title="Toggle Theme" aria-label="Toggle Theme">
            <i class="bi bi-moon-stars"></i>
        </button>
    </div>

    <div class="fb-auth-content">
        <!-- Left Brand Section (Facebook Style) -->
        <div class="fb-brand-col fade-up">
            <div class="d-flex align-items-center gap-3 mb-2 justify-content-center justify-content-lg-start">
                <img src="assets/img/logo.svg" alt="OmniSphere" class="fb-brand-logo" width="62" height="62">
                <h1 class="fb-brand-name mb-0">omnisphere</h1>
            </div>
            <h2 class="fb-brand-slogan" data-i18n="slogan">
                OmniSphere helps you connect and share with the people in your life.
            </h2>
        </div>

        <!-- Right Login Card Section (Facebook Style) -->
        <div class="fb-form-col fade-up">
            <div class="fb-login-box">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger py-2 px-3 small mb-3 rounded-3 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                        <span><?= e($errors[0]) ?></span>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?= e(url('auth', 'login')) ?>" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <input type="text" id="username" name="username" class="form-control fb-input" placeholder="Username" value="<?= e($username) ?>" autocomplete="username" required data-i18n-placeholder="username_placeholder">
                        <div class="invalid-feedback" data-i18n="username_required">Please enter your username.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" id="password" name="password" class="form-control fb-input pe-5" placeholder="Password" autocomplete="current-password" required data-i18n-placeholder="password_placeholder">
                        <button type="button" class="eye-btn" data-toggle-password="#password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                        <div class="invalid-feedback" data-i18n="password_required">Please enter your password.</div>
                    </div>
                    <button class="btn btn-caila btn-fb-login w-100 shadow-sm" type="submit" data-i18n="login_btn">
                        Log In
                    </button>
                    <div class="text-center my-3">
                        <a href="#" class="small text-decoration-none fb-forgot-link" id="forgotAccountBtn" data-i18n="forgot_link">Forgotten account?</a>
                    </div>
                    <hr class="fb-divider my-3">
                    <div class="text-center">
                        <a href="<?= e(url('auth', 'register')) ?>" class="btn btn-fb-register" data-i18n="create_account_btn">
                            Create new account
                        </a>
                    </div>
                </form>
            </div>
            <p class="fb-bottom-tagline text-center mt-4">
                <a href="<?= e(url('auth', 'register')) ?>" class="fw-bold text-reset text-decoration-none" data-i18n="bottom_link">Create a Profile</a> <span data-i18n="bottom_text">for your student community on OmniSphere.</span>
            </p>
        </div>
    </div>

    <!-- Facebook Style Auth Footer with Interactive Languages -->
    <footer class="fb-auth-footer">
        <div class="fb-footer-inner">
            <div class="fb-languages mb-2" id="langSelector">
                <a href="javascript:void(0)" class="lang-link active" data-lang="en">English (US)</a> &middot;
                <a href="javascript:void(0)" class="lang-link" data-lang="fil">Filipino</a> &middot;
                <a href="javascript:void(0)" class="lang-link" data-lang="bis">Bisaya</a> &middot;
                <a href="javascript:void(0)" class="lang-link" data-lang="ceb">Cebuano</a> &middot;
                <a href="javascript:void(0)" class="lang-link" data-lang="es">Español</a> &middot;
                <a href="javascript:void(0)" class="lang-link" data-lang="ja">日本語</a>
            </div>
            <hr class="my-2 opacity-25">
            <div class="fb-links small text-muted">
                <a href="<?= e(url('auth', 'register')) ?>" data-i18n="footer_signup">Sign Up</a> &middot;
                <a href="<?= e(url('auth', 'login')) ?>" data-i18n="footer_login">Log In</a> &middot;
                <span>OmniSphere &copy; <?= date('Y') ?></span> &middot;
                <span>SMCC Web Systems &amp; Technologies</span>
            </div>
        </div>
    </footer>
</div>

<!-- Modal for Forgotten Account Hint -->
<div class="modal fade" id="forgotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content card-caila p-2">
            <div class="modal-body text-center p-3">
                <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                    <i class="bi bi-key-fill fs-2"></i>
                </div>
                <h5 class="fw-bold mb-2" data-i18n="forgot_modal_title">Demo Accounts</h5>
                <p class="small text-muted mb-3" data-i18n="forgot_modal_body">
                    You can log in with any demo account:<br>
                    <strong>john</strong>, <strong>juan</strong>, <strong>maria</strong>, or <strong>pedro</strong>.<br>
                    Password for all: <code class="text-primary">password</code>
                </p>
                <button type="button" class="btn btn-caila btn-sm w-100" data-bs-dismiss="modal">Got it</button>
            </div>
        </div>
    </div>
</div>
