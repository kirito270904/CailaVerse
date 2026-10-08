<div class="auth-wrap">
    <section class="auth-hero">
        <div class="auth-hero-inner">
            <div class="auth-hero-logo">
                <img src="assets/img/logo.svg" alt="CailaVerse" width="44" height="44">
            </div>
            <h1 class="auth-hero-title">Join<br>CailaVerse Today</h1>
            <p class="auth-hero-sub">Create your profile, share ideas, and connect with the community in seconds.</p>
            <ul class="auth-hero-features">
                <li><i class="bi bi-person-plus-fill"></i> Create your profile in seconds</li>
                <li><i class="bi bi-image-fill"></i> Add a profile picture</li>
                <li><i class="bi bi-search-heart-fill"></i> Discover new people</li>
                <li><i class="bi bi-shield-lock-fill"></i> Safe &amp; secure platform</li>
            </ul>
        </div>
        <small class="position-relative" style="z-index:2; opacity:.7;">&copy; <?= date('Y') ?> CailaVerse &middot; SMCC Web Systems &amp; Technologies</small>
    </section>

    <section class="auth-side">
        <div class="auth-card fade-up">
            <div class="d-lg-none text-center mb-4">
                <img src="assets/img/logo.svg" alt="CailaVerse" width="56" height="56">
                <h5 class="fw-bold mt-2 mb-0" style="background:var(--caila-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">CailaVerse</h5>
            </div>
            <h2 class="fw-bold mb-1">Create your account</h2>
            <p class="text-muted mb-4">It only takes a minute to get started.</p>
            <form method="post" action="<?= e(url('auth', 'register')) ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="full_name">Full Name</label>
                    <div class="input-icon">
                        <i class="bi bi-person-badge"></i>
                        <input type="text" id="full_name" name="full_name" class="form-control" maxlength="100" value="<?= e($old['full_name']) ?>" required>
                    </div>
                    <div class="invalid-feedback">Full name is required.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="reg_username">Username</label>
                    <div class="input-icon">
                        <i class="bi bi-at"></i>
                        <input type="text" id="reg_username" name="username" class="form-control" pattern="[A-Za-z0-9_]{3,20}" value="<?= e($old['username']) ?>" autocomplete="username" required>
                    </div>
                    <div class="invalid-feedback">3–20 characters: letters, numbers, underscore.</div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" for="reg_password">Password</label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="reg_password" name="password" class="form-control" minlength="6" autocomplete="new-password" required>
                            <button type="button" class="eye-btn" data-toggle-password="#reg_password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                        </div>
                        <div class="invalid-feedback">At least 6 characters.</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold" for="reg_confirm">Confirm</label>
                        <div class="input-icon">
                            <i class="bi bi-shield-lock"></i>
                            <input type="password" id="reg_confirm" name="confirm" class="form-control" autocomplete="new-password" required>
                        </div>
                        <div class="invalid-feedback">Passwords must match.</div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Profile Picture <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*" data-preview="#reg-preview">
                    <div id="reg-preview" class="preview-box preview-round d-none mt-2"></div>
                </div>
                <button class="btn btn-caila btn-lg w-100" type="submit">
                    <i class="bi bi-person-plus-fill"></i> Create Account
                </button>
            </form>
            <p class="text-center mt-4 mb-0 small">Already have an account? <a href="<?= e(url('auth', 'login')) ?>" class="fw-bold" style="color:var(--caila-primary);">Login</a></p>
        </div>
    </section>
</div>
