<?php $title = t('edit_service') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('edit_service') ?></h2>

<div class="card">
    <form method="POST" action="<?= BASE_URL ?>/admin/services" data-validate>
        <input type="hidden" name="update_service" value="1">
        <input type="hidden" name="id" value="<?= $service->id ?>">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label"><?= t('name') ?>:</label>
                <input type="text" name="name" class="form-control" 
                       value="<?= htmlspecialchars($service->name) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= t('service_type') ?>:</label>
                <select name="type" class="form-control" required>
                    <option value="individual" <?= $service->type === 'individual' ? 'selected' : '' ?>><?= t('individual') ?></option>
                    <option value="package" <?= $service->type === 'package' ? 'selected' : '' ?>><?= t('package_type') ?></option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('description') ?>:</label>
            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($service->description) ?></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('price') ?> (R$):</label>
            <input type="number" name="price" class="form-control" step="0.01" min="0" 
                   value="<?= $service->price ?>" required>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary"><?= t('save_changes') ?></button>
            <a href="<?= BASE_URL ?>/admin/services" class="btn btn-secondary"><?= t('cancel') ?></a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>