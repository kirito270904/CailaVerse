<?php
$cur = $_GET['c'] ?? 'post';
$postCount = (int) (db()->query('SELECT COUNT(*) FROM posts WHERE user_id = ' . (int) $me['id'])->fetchColumn() ?: 0);
$friendsCount = (new FollowModel())->followersCount((int) $me['id']);
$isOnline = isset($me['last_active']) ? is_user_online($me['last_active']) : true;
?>
<!-- User Mini Profile Card -->
<div class="card card-caila user-mini-card mb-3">
    <div class="user-mini-banner">
        <div class="network-grid-pattern"></div>
    </div>
    <div class="card-body pt-0 pb-3 text-center">
        <a href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>" class="user-mini-avatar-wrap">
            <?= avatar($me, 76, true) ?>
        </a>
        <h6 class="user-mini-name mt-2 mb-0">
            <a href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>" class="text-decoration-none text-reset fw-bold">
                <?= e($me['full_name']) ?>
                <i class="bi bi-patch-check-fill text-info ms-1" title="Verified Member" style="font-size: 0.88rem;"></i>
            </a>
        </h6>
        <small class="text-muted d-block">@<?= e($me['username']) ?></small>

        <?php if (!empty($me['bio'])): ?>
            <p class="user-mini-bio mt-2 mb-0 text-truncate-2"><?= e($me['bio']) ?></p>
        <?php else: ?>
            <div class="network-status-chip mt-2 <?= $isOnline ? 'status-chip-online' : 'status-chip-offline' ?>">
                <span class="<?= $isOnline ? 'pulse-dot' : 'offline-dot' ?>"></span>
                <span><?= $isOnline ? 'Active on OmniSphere' : 'Offline' ?></span>
            </div>
        <?php endif; ?>

        <!-- Quick Stats Row (Clean 3-col: Posts, Friends, Status — No Node ID) -->
        <div class="user-stats-bar mt-3 pt-2 border-top d-flex justify-content-around">
            <div class="stat-col text-center">
                <div class="stat-number fw-bold"><?= $postCount ?></div>
                <div class="stat-label small text-muted">Posts</div>
            </div>
            <div class="stat-col text-center">
                <div class="stat-number fw-bold text-info"><?= $friendsCount ?></div>
                <div class="stat-label small text-muted">Friends</div>
            </div>
            <div class="stat-col text-center">
                <div class="stat-number fw-bold <?= $isOnline ? 'text-success' : 'text-muted' ?> d-flex align-items-center justify-content-center gap-1">
                    <span class="<?= $isOnline ? 'pulse-dot' : 'offline-dot' ?>"></span>
                    <span><?= $isOnline ? 'Online' : 'Off' ?></span>
                </div>
                <div class="stat-label small text-muted">Status</div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Menu -->
<nav class="card card-caila menu-card">
    <a class="menu-link <?= $cur === 'post' ? 'active' : '' ?>" href="<?= e(url()) ?>">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Newsfeed</span>
    </a>
    <a class="menu-link <?= $cur === 'search' ? 'active' : '' ?>" href="<?= e(url('search', 'index')) ?>">
        <i class="bi bi-compass-fill"></i>
        <span>Explore Network</span>
    </a>
    <a class="menu-link <?= ($cur === 'profile' && ($_GET['a'] ?? '') !== 'edit') ? 'active' : '' ?>" href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>">
        <i class="bi bi-person-bounding-box"></i>
        <span>My Profile</span>
    </a>
    <a class="menu-link <?= ($cur === 'profile' && ($_GET['a'] ?? '') === 'edit') ? 'active' : '' ?>" href="<?= e(url('profile', 'edit')) ?>">
        <i class="bi bi-sliders"></i>
        <span>Edit Profile</span>
    </a>
</nav>
