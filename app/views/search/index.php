<div class="mx-auto" style="max-width: 780px;">
    <form action="index.php" method="get" class="card card-caila p-4 mb-4 fade-up">
        <input type="hidden" name="c" value="search">
        <input type="hidden" name="a" value="index">
        <div class="d-flex gap-2">
            <div class="position-relative flex-grow-1">
                <i class="bi bi-search position-absolute" style="left:16px;top:50%;transform:translateY(-50%);color:var(--caila-text-light);z-index:2;"></i>
                <input type="search" name="q" class="form-control form-control-lg" style="padding-left:46px;border-radius:var(--caila-radius-pill);" placeholder="Search people by name or posts by keyword..." value="<?= e($q) ?>" required>
            </div>
            <button class="btn btn-caila px-4" type="submit" style="border-radius:var(--caila-radius-pill);">Search</button>
        </div>
    </form>

<?php if ($q === ''): ?>
    <div class="empty-state fade-up">
        <i class="bi bi-search-heart"></i>
        <p>Find people and posts across CailaVerse.</p>
    </div>
<?php else: ?>
    <h5 class="fw-bold mb-3 fade-up d-flex align-items-center gap-2">
        <i class="bi bi-search text-primary"></i>
        Results for &ldquo;<?= e($q) ?>&rdquo;
    </h5>

    <h6 class="mt-3 mb-3 text-uppercase small fw-bold text-muted d-flex align-items-center gap-2">
        <i class="bi bi-people-fill text-primary"></i> People
    </h6>
    <?php if (!$users): ?>
        <div class="empty-state py-3"><i class="bi bi-person-x" style="font-size:2rem;"></i><p class="small">No users found.</p></div>
    <?php endif; ?>
    <?php foreach ($users as $u): ?>
        <div class="card card-caila mb-2 fade-up">
            <div class="card-body d-flex align-items-center justify-content-between gap-3 py-3 px-4">
                <a class="d-flex align-items-center gap-3 text-decoration-none min-w-0 flex-grow-1 text-reset" href="<?= e(url('profile', 'show', ['id' => $u['id']])) ?>">
                    <?= avatar($u, 44, true) ?>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold post-author"><?= e($u['full_name']) ?></div>
                        <small class="text-muted">@<?= e($u['username']) ?></small>
                        <?php if (!empty($u['bio'])): ?>
                            <small class="text-muted d-block text-truncate mt-1"><?= e($u['bio']) ?></small>
                        <?php endif; ?>
                    </div>
                </a>
                <form method="post" action="<?= e(url('follow', 'toggle')) ?>" data-ajax="follow" class="m-0 flex-shrink-0">
                    <?= csrf_field() ?>
                    <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                    <button type="submit" class="btn btn-connect-action <?= !empty($u['is_following']) ? 'following' : '' ?>" title="<?= !empty($u['is_following']) ? 'Friends' : 'Add Friend' ?>">
                        <i class="bi <?= !empty($u['is_following']) ? 'bi-person-check-fill' : 'bi-person-plus-fill' ?>"></i>
                        <span class="connect-btn-text"><?= !empty($u['is_following']) ? 'Friends' : 'Add' ?></span>
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <h6 class="mt-4 mb-3 text-uppercase small fw-bold text-muted d-flex align-items-center gap-2">
        <i class="bi bi-chat-square-text-fill text-primary"></i> Posts
    </h6>
    <div id="feed">
        <?php if (!$posts): ?>
            <div class="empty-state py-3" id="empty-feed"><i class="bi bi-search" style="font-size:2rem;"></i><p class="small">No posts found.</p></div>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/../partials/post.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>
