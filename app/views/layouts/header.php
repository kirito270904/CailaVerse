<?php $isAuth = ($layout ?? '') === 'auth'; ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'CailaVerse') ?> | CailaVerse Social Network</title>
    <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg">
    <link rel="alternate icon" type="image/png" href="assets/img/logo.png">
    <script>
        try {
            var savedTheme = localStorage.getItem('caila-theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        } catch (err) {}
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../../public/assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="<?= $isAuth ? 'auth-body' : '' ?>">
<div id="toast-area" aria-live="polite"></div>

<?php if (!$isAuth): ?>
<nav class="navbar navbar-expand-lg navbar-caila sticky-top">
    <div class="nav-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(url()) ?>">
            <div class="nav-logo-wrap">
                <img src="assets/img/logo.svg" alt="CailaVerse" class="nav-logo">
            </div>
            <div class="brand-text">
                <span class="brand-title">CailaVerse</span>
                <span class="brand-tagline">Social Network</span>
            </div>
        </a>

        <?php if ($me): ?>
            <!-- Desktop Search Bar -->
            <form class="nav-search d-none d-md-block mx-auto" action="index.php" method="get">
                <input type="hidden" name="c" value="search">
                <input type="hidden" name="a" value="index">
                <i class="bi bi-search"></i>
                <input class="form-control" type="search" name="q" placeholder="Search people, posts, or #tags..." value="<?= e($_GET['q'] ?? '') ?>" required>
            </form>

            <div class="d-flex align-items-center gap-2">
                <!-- Live Network Status Pill -->
                <span class="network-badge-live d-none d-xl-inline-flex align-items-center me-2">
                    <span class="pulse-dot me-1"></span>
                    <span>Online</span>
                </span>

                <a class="btn-nav-action d-none d-sm-inline-flex" href="<?= e(url()) ?>" title="Newsfeed">
                    <i class="bi bi-grid-1x2-fill"></i> <span>Feed</span>
                </a>
                <a class="btn-nav-action d-none d-sm-inline-flex" href="<?= e(url('search', 'index')) ?>" title="Explore Network">
                    <i class="bi bi-compass-fill"></i> <span>Explore</span>
                </a>

                <button class="btn-theme-toggle" id="themeToggle" type="button" title="Toggle dark/light mode" aria-label="Toggle dark/light mode">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>

                <div class="dropdown">
                    <a class="nav-user-chip dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= avatar($me, 32, true) ?>
                        <span class="d-none d-sm-inline"><?= e($me['username']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="px-3 py-2 border-bottom mb-1">
                            <div class="fw-bold text-truncate" style="max-width: 180px;"><?= e($me['full_name']) ?></div>
                            <small class="text-muted">@<?= e($me['username']) ?></small>
                        </li>
                        <li><a class="dropdown-item" href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>"><i class="bi bi-person-circle me-2 text-primary"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="<?= e(url('profile', 'edit')) ?>"><i class="bi bi-sliders me-2 text-info"></i>Edit Profile</a></li>
                        <li><a class="dropdown-item" href="<?= e(url('search', 'index')) ?>"><i class="bi bi-compass me-2 text-success"></i>Explore Network</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="<?= e(url('auth', 'logout')) ?>" method="post" class="m-0">
                                <?= csrf_field() ?>
                                <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<?php endif; ?>

<main class="<?= $isAuth ? 'auth-main' : 'page-wide' ?>">
