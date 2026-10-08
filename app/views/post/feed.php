<div class="feed-grid">
    <aside class="feed-left">
        <div class="sticky-side">
            <?php require __DIR__ . '/../partials/sidebar_left.php'; ?>
        </div>
    </aside>

    <section class="feed-main">
        <!-- Modern Cyber-Networking Post Composer -->
        <div class="composer mb-4 fade-up">
            <form method="post" action="<?= e(url('post', 'store')) ?>" enctype="multipart/form-data" data-ajax="post">
                <?= csrf_field() ?>
                <div class="d-flex gap-3 align-items-start">
                    <a href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>">
                        <?= avatar($me, 46, true) ?>
                    </a>
                    <div class="flex-grow-1 min-w-0">
                        <textarea name="content" class="composer-text" rows="3" maxlength="1000"
                                  placeholder="What's on your mind, <?= e(explode(' ', $me['full_name'])[0]) ?>?"
                                  data-counter="#post-counter" required></textarea>
                    </div>
                </div>

                <div id="post-preview" class="preview-box d-none mt-3 ps-sm-5"></div>

                <!-- Quick Tags & Toolbar -->
                <div class="composer-toolbar d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <label class="btn btn-soft btn-sm mb-0 cursor-pointer" title="Attach an image">
                            <i class="bi bi-image text-info"></i>
                            <span>Photo</span>
                            <input type="file" name="image" accept="image/*" hidden data-preview="#post-preview">
                        </label>

                        <div class="quick-tags d-none d-sm-flex align-items-center gap-1">
                            <button type="button" class="tag-chip" data-insert-tag="#OmniSphere">#OmniSphere</button>
                            <button type="button" class="tag-chip" data-insert-tag="#WebTech">#WebTech</button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="counter-pill" id="post-counter">0/1000</span>
                        <button class="btn btn-caila" type="submit">
                            <i class="bi bi-send-fill"></i>
                            <span>Post</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Newsfeed Timeline -->
        <div id="feed">
            <?php if (!$posts): ?>
                <div class="empty-state fade-up" id="empty-feed">
                    <i class="bi bi-chat-square-text"></i>
                    <p>No posts yet. Be the first to share something!</p>
                </div>
            <?php endif; ?>
            <?php foreach ($posts as $post): ?>
                <?php require __DIR__ . '/../partials/post.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <aside class="feed-right">
        <div class="sticky-side">
            <?php require __DIR__ . '/../partials/sidebar_right.php'; ?>
        </div>
    </aside>
</div>
