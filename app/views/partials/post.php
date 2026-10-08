<?php
$isOwner = (int) $post['user_id'] === (int) $me['id'];
$likeCount = (int) $post['like_count'];
$shareCount = (int) ($post['share_count'] ?? 0);
$myReaction = $post['my_reaction'] ?? null;
?>
<article class="card card-caila post-card mb-3 fade-up" id="post-<?= (int) $post['id'] ?>" data-post-id="<?= (int) $post['id'] ?>">
    <div class="card-body p-0">
        <!-- Post Header -->
        <header class="d-flex align-items-center gap-3 px-4 pt-4 pb-2">
            <a href="<?= e(url('profile', 'show', ['id' => $post['user_id']])) ?>">
                <?= avatar($post, 44, true) ?>
            </a>
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <a class="post-author d-block text-truncate fw-bold" href="<?= e(url('profile', 'show', ['id' => $post['user_id']])) ?>">
                        <?= e($post['full_name']) ?>
                    </a>
                    <?php if (!empty($post['shared_orig_id'])): ?>
                        <span class="text-muted small ms-1">
                            shared a post
                        </span>
                    <?php endif; ?>
                    <span class="badge-network-pill small ms-1">
                        <i class="bi bi-globe2 text-info"></i>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-1 text-muted small flex-wrap">
                    <span class="post-handle">@<?= e($post['username']) ?></span>
                    <span class="post-handle">&middot;</span>
                    <span class="post-time" title="<?= e(format_date($post['created_at'])) ?>"><?= e(time_ago($post['created_at'])) ?></span>
                </div>
            </div>
            <?php if ($isOwner): ?>
                <div class="d-flex gap-1">
                    <a class="btn btn-soft btn-sm" href="<?= e(url('post', 'edit', ['id' => $post['id']])) ?>" title="Edit post"><i class="bi bi-pencil"></i></a>
                    <form method="post" action="<?= e(url('post', 'delete')) ?>" data-confirm="Delete this post?" data-ajax="delete-post" class="m-0">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                        <button class="btn btn-soft btn-sm btn-soft-danger" type="submit" title="Delete post"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            <?php endif; ?>
        </header>

        <!-- Post Content (Sharer's thoughts if shared) -->
        <?php if (!empty($post['content'])): ?>
            <div class="px-4 pb-2">
                <p class="post-content mb-2"><?= nl2br(e($post['content'])) ?></p>
            </div>
        <?php endif; ?>

        <!-- Embedded Shared Post (Facebook-Style Shared Card) -->
        <?php if (!empty($post['shared_orig_id'])): ?>
            <div class="shared-post-embed mx-4 mb-3 p-3 rounded-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="<?= e(url('profile', 'show', ['id' => $post['shared_user_id']])) ?>">
                        <?= avatar([
                            'profile_image' => $post['shared_profile_image'],
                            'full_name'     => $post['shared_full_name'],
                            'username'      => $post['shared_username']
                        ], 34, true) ?>
                    </a>
                    <div class="min-w-0">
                        <a class="fw-bold small text-decoration-none text-reset d-block text-truncate" href="<?= e(url('profile', 'show', ['id' => $post['shared_user_id']])) ?>">
                            <?= e($post['shared_full_name']) ?>
                        </a>
                        <small class="text-muted d-block" style="font-size:0.75rem;">
                            @<?= e($post['shared_username']) ?> &middot; <?= e(time_ago($post['shared_created_at'])) ?>
                        </small>
                    </div>
                </div>
                <?php if (!empty($post['shared_content'])): ?>
                    <p class="shared-post-text small mb-2"><?= nl2br(e($post['shared_content'])) ?></p>
                <?php endif; ?>
                <?php if (!empty($post['shared_image'])): ?>
                    <div class="post-image-wrap mb-1">
                        <img src="uploads/<?= e($post['shared_image']) ?>" class="post-image" alt="Shared media" style="max-height:360px;" loading="lazy">
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif (!empty($post['image'])): ?>
            <!-- Regular Post Image -->
            <div class="post-image-wrap mx-4 mb-3">
                <a href="uploads/<?= e($post['image']) ?>" target="_blank" rel="noopener">
                    <img src="uploads/<?= e($post['image']) ?>" class="post-image" alt="Post media" loading="lazy">
                </a>
            </div>
        <?php endif; ?>

        <!-- Post Stats Bar (Reactions summary & comments/shares count) -->
        <div class="post-stats-line px-4 py-2 border-top d-flex align-items-center justify-content-between text-muted small">
            <div class="d-flex align-items-center gap-1">
                <span class="reactions-summary-icons">
                    <span class="react-mini-badge">👍</span>
                    <span class="react-mini-badge">❤️</span>
                </span>
                <span class="like-count fw-semibold"><?= $likeCount ?></span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span><span class="comment-count"><?= count($post['comments']) ?></span> comments</span>
                <span><span class="share-count"><?= $shareCount ?></span> shares</span>
            </div>
        </div>

        <!-- Facebook-Style Action Buttons Bar -->
        <div class="post-actions px-4 py-2 border-top d-flex align-items-center justify-content-around">
            <!-- Facebook Reactions Trigger & Floating Dock -->
            <div class="reaction-wrapper position-relative flex-grow-1 text-center">
                <div class="fb-reactions-dock">
                    <button type="button" class="reaction-item" data-reaction="like" title="Like">
                        <span class="reaction-emoji">👍</span>
                        <span class="reaction-tooltip">Like</span>
                    </button>
                    <button type="button" class="reaction-item" data-reaction="love" title="Love">
                        <span class="reaction-emoji">❤️</span>
                        <span class="reaction-tooltip">Love</span>
                    </button>
                    <button type="button" class="reaction-item" data-reaction="care" title="Care">
                        <span class="reaction-emoji">🥰</span>
                        <span class="reaction-tooltip">Care</span>
                    </button>
                    <button type="button" class="reaction-item" data-reaction="haha" title="Haha">
                        <span class="reaction-emoji">😆</span>
                        <span class="reaction-tooltip">Haha</span>
                    </button>
                    <button type="button" class="reaction-item" data-reaction="wow" title="Wow">
                        <span class="reaction-emoji">😮</span>
                        <span class="reaction-tooltip">Wow</span>
                    </button>
                    <button type="button" class="reaction-item" data-reaction="sad" title="Sad">
                        <span class="reaction-emoji">😢</span>
                        <span class="reaction-tooltip">Sad</span>
                    </button>
                    <button type="button" class="reaction-item" data-reaction="angry" title="Angry">
                        <span class="reaction-emoji">😡</span>
                        <span class="reaction-tooltip">Angry</span>
                    </button>
                </div>

                <form method="post" action="<?= e(url('post', 'like')) ?>" data-ajax="react" class="m-0 reaction-form d-inline-block w-100">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
                    <input type="hidden" name="reaction" class="reaction-input" value="<?= e($myReaction ?? 'like') ?>">
                    <button class="fb-action-btn react-btn <?= $post['liked'] ? ('reacted-' . e($myReaction ?? 'like')) : '' ?>" type="submit">
                        <span class="react-icon-holder"><?= reaction_icon($myReaction) ?></span>
                        <span class="react-label"><?= reaction_label($myReaction) ?></span>
                    </button>
                </form>
            </div>

            <!-- Comment Trigger Button -->
            <button type="button" class="fb-action-btn flex-grow-1 text-center" onclick="this.closest('.post-card').querySelector('.comment-input').focus()">
                <i class="bi bi-chat-dots-fill text-muted me-1"></i>
                <span>Comment</span>
            </button>

            <!-- Facebook-Style Share Trigger Button -->
            <button type="button" class="fb-action-btn flex-grow-1 text-center open-share-modal-btn" 
                    data-post-id="<?= (int) $post['id'] ?>"
                    data-post-author="<?= e($post['full_name']) ?>"
                    data-post-content="<?= e(mb_substr($post['content'] ?: ($post['shared_content'] ?? ''), 0, 120)) ?>"
                    title="Share to feed">
                <i class="bi bi-share-fill text-muted me-1"></i>
                <span>Share</span>
            </button>
        </div>

        <!-- Comments Section -->
        <div class="comments-wrapper px-4 pb-4">
            <div class="comment-list">
                <?php foreach ($post['comments'] as $comment): ?>
                    <?php require __DIR__ . '/comment.php'; ?>
                <?php endforeach; ?>
            </div>
            <form method="post" action="<?= e(url('comment', 'store')) ?>" data-ajax="comment" class="comment-form mt-2">
                <?= csrf_field() ?>
                <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                <?= avatar($me, 32, true) ?>
                <input type="text" name="content" class="form-control comment-input" placeholder="Write a comment..." maxlength="500" autocomplete="off" required>
                <button class="btn-send flex-shrink-0" type="submit" title="Send comment"><i class="bi bi-send-fill"></i></button>
            </form>
        </div>
    </div>
</article>
