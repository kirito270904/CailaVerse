<div class="card card-caila mx-auto fade-up" style="max-width: 560px;">
    <div class="card-header">
        <i class="bi bi-chat-left-text text-primary"></i>
        <span>Edit Comment</span>
    </div>
    <div class="card-body p-4">
        <form method="post" action="<?= e(url('comment', 'update')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $comment['id'] ?>">
            <textarea name="content" class="form-control mb-3" rows="3" maxlength="500" data-counter="#comment-counter" required><?= e($comment['content']) ?></textarea>
            <div class="text-end mb-3"><small class="text-muted" id="comment-counter">0/500</small></div>
            <div class="d-flex gap-2">
                <button class="btn btn-caila" type="submit"><i class="bi bi-check2-circle"></i> Save Changes</button>
                <a class="btn btn-soft" href="<?= e(url()) ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
