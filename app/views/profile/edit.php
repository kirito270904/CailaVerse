<div class="card card-caila mx-auto fade-up" style="max-width: 520px;">
    <div class="card-header">
        <i class="bi bi-person-gear text-primary"></i>
        <span>Edit Profile</span>
    </div>
    <div class="card-body p-4">
        <form method="post" action="<?= e(url('profile', 'update')) ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <div class="text-center mb-4"><?= avatar($me, 96) ?></div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="full_name" class="form-control" maxlength="100" value="<?= e($me['full_name']) ?>" required>
                <div class="invalid-feedback">Full name is required.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Bio</label>
                <textarea name="bio" class="form-control" rows="3" maxlength="255" data-counter="#bio-counter" placeholder="Tell people about yourself..."><?= e($me['bio']) ?></textarea>
                <div class="text-end"><small class="text-muted" id="bio-counter">0/255</small></div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Profile Photo</label>
                <input type="file" name="profile_image" class="form-control" accept="image/*" data-preview="#profile-preview">
                <div id="profile-preview" class="preview-box preview-round d-none mt-2"></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-caila" type="submit"><i class="bi bi-check2-circle"></i> Save Changes</button>
                <a class="btn btn-soft" href="<?= e(url('profile', 'show', ['id' => $me['id']])) ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
