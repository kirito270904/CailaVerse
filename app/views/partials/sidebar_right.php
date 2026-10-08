<!-- Discover People / Suggested Connections -->
<div class="card card-caila mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span class="d-flex align-items-center gap-2">
            <i class="bi bi-people-fill text-info"></i>
            <span>Network Connections</span>
        </span>
        <span class="badge bg-primary-subtle text-primary small">Suggested</span>
    </div>
    <div class="card-body p-2">
        <?php if (!$people): ?>
            <p class="text-muted small text-center py-3 mb-0">No other connections found.</p>
        <?php endif; ?>
        <?php foreach ($people as $person): ?>
            <div class="people-item d-flex align-items-center gap-2">
                <a href="<?= e(url('profile', 'show', ['id' => $person['id']])) ?>" class="d-flex align-items-center gap-2 min-w-0 flex-grow-1 text-decoration-none text-reset">
                    <?= avatar($person, 38, true) ?>
                    <span class="min-w-0 flex-grow-1">
                        <span class="d-block text-truncate people-name fw-semibold"><?= e($person['full_name']) ?></span>
                        <small class="text-muted d-block text-truncate">
                            @<?= e($person['username']) ?> &middot;
                            <?php $pOnline = is_user_online($person['last_active'] ?? null); ?>
                            <span class="<?= $pOnline ? 'text-success fw-medium' : 'text-muted' ?>">
                                <span class="<?= $pOnline ? 'pulse-dot' : 'offline-dot' ?> me-1" style="width:6px;height:6px;display:inline-block;vertical-align:middle;"></span><?= $pOnline ? 'Online' : 'Off' ?>
                            </span>
                        </small>
                    </span>
                </a>
                <form method="post" action="<?= e(url('follow', 'toggle')) ?>" data-ajax="follow" class="m-0 flex-shrink-0">
                    <?= csrf_field() ?>
                    <input type="hidden" name="user_id" value="<?= (int) $person['id'] ?>">
                    <button type="submit" class="btn btn-connect-action <?= !empty($person['is_following']) ? 'following' : '' ?>" title="<?= !empty($person['is_following']) ? 'Friends' : 'Add Friend' ?>">
                        <i class="bi <?= !empty($person['is_following']) ? 'bi-person-check-fill' : 'bi-person-plus-fill' ?>"></i>
                        <span class="connect-btn-text"><?= !empty($person['is_following']) ? 'Friends' : 'Add' ?></span>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Trending Topics / Network Hashtags -->
<div class="card card-caila mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span class="d-flex align-items-center gap-2">
            <i class="bi bi-fire text-danger"></i>
            <span>Trending in Network</span>
        </span>
        <span class="badge bg-success-subtle text-success small">Live</span>
    </div>
    <div class="card-body p-2">
        <a class="trend-item" href="<?= e(url('search', 'index', ['q' => 'OmniSphere'])) ?>">
            <span class="trend-meta">Trending in Philippines</span>
            <span class="trend-tag">#OmniSphere</span>
            <span class="trend-count">1.4k posts &middot; Active</span>
        </a>
        <a class="trend-item" href="<?= e(url('search', 'index', ['q' => 'web'])) ?>">
            <span class="trend-meta">Technology &middot; Coding</span>
            <span class="trend-tag">#WebDevelopment</span>
            <span class="trend-count">890 posts &middot; Hot</span>
        </a>
        <a class="trend-item" href="<?= e(url('search', 'index', ['q' => 'tech'])) ?>">
            <span class="trend-meta">Computing &middot; Network</span>
            <span class="trend-tag">#TechTrends2026</span>
            <span class="trend-count">620 posts</span>
        </a>
        <a class="trend-item" href="<?= e(url('search', 'index', ['q' => 'programming'])) ?>">
            <span class="trend-meta">Software &middot; Community</span>
            <span class="trend-tag">#DevCommunity</span>
            <span class="trend-count">410 posts</span>
        </a>
    </div>
</div>

<!-- Clean Network Info & Links -->
<div class="network-mini-footer px-3 text-muted small">
    <div class="d-flex flex-wrap gap-2 mb-2 footer-network-links">
        <a href="<?= e(url()) ?>">Home</a> &middot;
        <a href="<?= e(url('search', 'index')) ?>">Explore</a> &middot;
        <a href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>">Profile</a> &middot;
        <span>Privacy</span> &middot;
        <span>Terms</span>
    </div>
    <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.78rem;">
        <span class="pulse-dot"></span>
        <span>OmniSphere Social Network &copy; <?= date('Y') ?></span>
    </div>
</div>
