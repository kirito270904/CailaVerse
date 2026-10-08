<div class="comment-item d-flex gap-2 mb-2" id="comment-<?= (int) $comment['id'] ?>">
    <a href="<?= e(url('profile', 'show', ['id' => $comment['user_id']])) ?>" class="flex-shrink-0"><?= avatar($comment, 32) ?></a>
    <div class="comment-bubble flex-grow-1">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div class="min-w-0">
                <a class="comment-author" href="<?= e(url('profile', 'show', ['id' => $comment['user_id']])) ?>"><?= e($comment['full_name']) ?></a>
                <small class="text-muted ms-1" title="<?= e(format_date($comment['created_at'])) ?>"><?= e(time_ago($comment['created_at'])) ?></small>
            </div>
            <?php if ((int) $comment['user_id'] === (int) $me['id']): ?>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <a class="icon-link" href="<?= e(url('comment', 'edit', ['id' => $comment['id']])) ?>" title="Edit"><i class="bi bi-pencil"></i></a>
                    <form method="post" action="<?= e(url('comment', 'delete')) ?>" data-confirm="Delete this comment?" data-ajax="delete-comment" class="m-0">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $comment['id'] ?>">
                        <button class="icon-link icon-danger" type="submit" title="Delete"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <div class="comment-text"><?= nl2br(e($comment['content'])) ?></div>
    </div>
</div>
