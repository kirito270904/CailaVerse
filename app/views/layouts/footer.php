<?php if (!$isAuth): ?>
<footer class="footer-caila mt-4 pt-3 border-top">
    <div class="footer-inner d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <img src="assets/img/logo.svg" alt="OmniSphere" height="22">
            <span class="fw-semibold text-reset">OmniSphere</span>
            <span class="text-muted small">&middot; &copy; <?= date('Y') ?> All Rights Reserved</span>
        </div>
        <div class="footer-links d-flex align-items-center gap-3 small text-muted">
            <a href="<?= e(url()) ?>" class="text-reset text-decoration-none">Feed</a>
            <a href="<?= e(url('search', 'index')) ?>" class="text-reset text-decoration-none">Explore</a>
            <?php if (!empty($me)): ?>
                <a href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>" class="text-reset text-decoration-none">Profile</a>
            <?php endif; ?>
            <span class="d-flex align-items-center gap-1 text-success">
                <span class="pulse-dot"></span>
                <span>Active</span>
            </span>
        </div>
    </div>
</footer>
<?php endif; ?>
</main>

<?php if (!$isAuth && !empty($me)): ?>
<?php $cur = $_GET['c'] ?? 'post'; ?>
<nav class="bottom-nav d-lg-none">
    <a href="<?= e(url()) ?>" class="<?= $cur === 'post' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Feed</span>
    </a>
    <a href="<?= e(url('search', 'index')) ?>" class="<?= $cur === 'search' ? 'active' : '' ?>">
        <i class="bi bi-compass-fill"></i>
        <span>Explore</span>
    </a>
    <a href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>" class="<?= $cur === 'profile' ? 'active' : '' ?>">
        <i class="bi bi-person-circle"></i>
        <span>Profile</span>
    </a>
</nav>
<?php endif; ?>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content card-caila p-2">
            <div class="modal-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-3">
                    <i class="bi bi-exclamation-triangle-fill fs-2"></i>
                </div>
                <h6 class="fw-bold mb-1">Confirm Action</h6>
                <p class="text-muted small mb-4" id="confirmText">Are you sure? This cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-sm btn-soft" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-danger px-3" id="confirmYes">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Facebook-Style Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-caila p-3">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-share-fill text-info"></i> Share Post
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= e(url('post', 'share')) ?>" data-ajax="share-post">
                <?= csrf_field() ?>
                <input type="hidden" name="post_id" id="sharePostId" value="">
                <div class="modal-body pt-0">
                    <?php if (!empty($me)): ?>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <?= avatar($me, 38, true) ?>
                        <div>
                            <div class="fw-bold small"><?= e($me['full_name']) ?></div>
                            <span class="badge bg-secondary-subtle text-secondary small"><i class="bi bi-globe2 me-1"></i> Public Feed</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <textarea name="content" id="shareThoughts" class="form-control composer-text mb-3" rows="3" placeholder="Say something about this post..."></textarea>
                    
                    <!-- Preview of post being shared -->
                    <div class="p-3 rounded-3 border bg-body-tertiary" id="sharePreviewBox">
                        <small class="text-info d-block fw-semibold mb-1" id="sharePreviewAuthor"></small>
                        <p class="small mb-0 text-muted" id="sharePreviewSnippet"></p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-2 d-flex justify-content-between">
                    <button type="button" class="btn btn-soft btn-sm share-copy-btn">
                        <i class="bi bi-link-45deg me-1"></i> Copy Link
                    </button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-soft btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-caila btn-sm px-4">
                            <i class="bi bi-send-fill me-1"></i> Share Now
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.CAILA_TOASTS = <?= json_encode($toasts ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    window.SMCC_TOASTS = window.CAILA_TOASTS;
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js?v=<?= filemtime(__DIR__ . '/../../../public/assets/js/app.js') ?>"></script>
</body>
</html>
