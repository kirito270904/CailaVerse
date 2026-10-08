<div class="profile-card-modern mx-auto fade-up" style="max-width: 820px;">
    <div class="profile-cover">
        <div class="network-grid-pattern"></div>
    </div>
    <div class="profile-body-modern">
        <div class="profile-header-row">
            <div class="d-flex align-items-end gap-3 flex-wrap">
                <div class="profile-avatar-wrap"><?= avatar($user, 120, true) ?></div>
                <div class="pb-1">
                    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <span><?= e($user['full_name']) ?></span>
                        <i class="bi bi-patch-check-fill text-info" title="Verified Network Member" style="font-size: 1.15rem;"></i>
                    </h3>
                    <div class="text-muted">@<?= e($user['username']) ?></div>
                </div>
            </div>
            <?php if ((int) $user['id'] === (int) $me['id']): ?>
                <a class="btn btn-caila btn-sm" href="<?= e(url('profile', 'edit')) ?>">
                    <i class="bi bi-sliders"></i> Edit Profile
                </a>
            <?php else: ?>
                <form method="post" action="<?= e(url('follow', 'toggle')) ?>" data-ajax="follow" class="m-0">
                    <?= csrf_field() ?>
                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                    <button type="submit" class="btn <?= !empty($isFollowing) ? 'btn-soft' : 'btn-caila' ?> btn-sm profile-follow-btn">
                        <i class="bi <?= !empty($isFollowing) ? 'bi-person-check-fill text-success' : 'bi-person-plus-fill' ?>"></i>
                        <span class="connect-btn-text"><?= !empty($isFollowing) ? 'Friends / Connected' : 'Add Friend' ?></span>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php $targetOnline = isset($user['last_active']) ? is_user_online($user['last_active']) : false; ?>
        <?php if (!empty($user['bio'])): ?>
            <p class="mb-3 mt-2 text-secondary" style="max-width: 600px; font-size: 0.98rem;"><?= e($user['bio']) ?></p>
        <?php else: ?>
            <div class="network-status-chip mb-3 mt-2 <?= $targetOnline ? 'status-chip-online' : 'status-chip-offline' ?>">
                <span class="<?= $targetOnline ? 'pulse-dot' : 'offline-dot' ?>"></span>
                <span><?= $targetOnline ? 'Active on CailaVerse' : 'Offline' ?></span>
            </div>
        <?php endif; ?>

        <div class="profile-meta-pills">
            <span class="meta-pill">
                <i class="bi bi-people-fill text-info"></i>
                <strong class="followers-count"><?= (int) ($followerCount ?? 0) ?></strong> friend<?= ($followerCount ?? 0) === 1 ? '' : 's' ?>
            </span>
            <span class="meta-pill">
                <i class="bi bi-grid-fill text-primary"></i>
                <strong><?= count($posts) ?></strong> post<?= count($posts) === 1 ? '' : 's' ?>
            </span>
            <span class="meta-pill">
                <i class="bi bi-calendar3 text-secondary"></i>
                Joined <?= e(date('F Y', strtotime($user['created_at']))) ?>
            </span>
            <span class="meta-pill <?= $targetOnline ? 'text-success' : 'text-muted' ?>">
                <span class="<?= $targetOnline ? 'pulse-dot' : 'offline-dot' ?>"></span>
                <strong class="ms-1"><?= $targetOnline ? 'Online' : 'Off' ?></strong>
            </span>
        </div>
    </div>
</div>

<div class="mx-auto" style="max-width: 780px;">
    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-file-post text-info"></i> Posts by <?= e($user['full_name']) ?>
    </h5>
    <div id="feed">
        <?php if (!$posts): ?>
            <div class="empty-state fade-up" id="empty-feed">
                <i class="bi bi-chat-square-text"></i>
                <p>No posts published yet.</p>
            </div>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>
            <?php require __DIR__ . '/../partials/post.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
