<div class="auth-wrap">
    <section class="auth-hero">
        <div class="auth-hero-inner">
            <div class="auth-hero-logo">
                <img src="assets/img/logo.svg" alt="CailaVerse" width="44" height="44">
            </div>
            <h1 class="auth-hero-title">Welcome to<br>CailaVerse</h1>
            <p class="auth-hero-sub">A simple, smooth &amp; unique social platform to stay connected with friends, share moments, and build community.</p>
            <ul class="auth-hero-features">
                <li><i class="bi bi-chat-heart-fill"></i> Share updates &amp; photos instantly</li>
                <li><i class="bi bi-heart-fill"></i> Like &amp; comment on posts</li>
                <li><i class="bi bi-people-fill"></i> Discover &amp; connect with people</li>
                <li><i class="bi bi-moon-stars-fill"></i> Beautiful light &amp; dark modes</li>
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
            <h2 class="fw-bold mb-1">Welcome back</h2>
            <p class="text-muted mb-4">Log in to continue to CailaVerse.</p>
            <form method="post" action="<?= e(url('auth', 'login')) ?>" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="username">Username</label>
                    <div class="input-icon">
                        <i class="bi bi-person"></i>
                        <input type="text" id="username" name="username" class="form-control" value="<?= e($username) ?>" autocomplete="username" required>
                    </div>
                    <div class="invalid-feedback">Please enter your username.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="password">Password</label>
                    <div class="input-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
                        <button type="button" class="eye-btn" data-toggle-password="#password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                    </div>
                    <div class="invalid-feedback">Please enter your password.</div>
                </div>
                <button class="btn btn-caila btn-lg w-100" type="submit">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
            <p class="text-center mt-4 mb-0 small">Don't have an account? <a href="<?= e(url('auth', 'register')) ?>" class="fw-bold" style="color:var(--caila-primary);">Create one</a></p>
        </div>
    </section>
</div>
