<div class="card card-caila mx-auto fade-up" style="max-width: 640px;">
    <div class="card-header">
        <i class="bi bi-pencil-square text-primary"></i>
        <span>Edit Post</span>
    </div>
    <div class="card-body p-4">
        <form method="post" action="<?= e(url('post', 'update')) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
            <textarea name="content" class="form-control mb-3" rows="4" maxlength="1000" data-counter="#edit-counter" required><?= e($post['content']) ?></textarea>
            <div class="text-end mb-3"><small class="text-muted" id="edit-counter">0/1000</small></div>
            <?php if (!empty($post['image'])): ?>
                <div class="mb-3">
                    <img src="uploads/<?= e($post['image']) ?>" class="rounded-3 border" style="max-height:220px; width:auto; max-width:100%;" alt="">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="rm">
                    <label class="form-check-label" for="rm">Remove current image</label>
                </div>
            <?php endif; ?>
            <div class="mb-4">
                <label class="form-label fw-semibold">Replace image <span class="text-muted fw-normal">(optional)</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" data-preview="#edit-preview">
                <div id="edit-preview" class="preview-box d-none mt-2"></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-caila" type="submit"><i class="bi bi-check2-circle"></i> Save Changes</button>
                <a class="btn btn-soft" href="<?= e(url()) ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
