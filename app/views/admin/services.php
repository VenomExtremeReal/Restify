<?php $title = t('service_management') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('service_management') ?></h2>

<!-- Formulário para criar novo serviço -->
<div class="card">
    <h3><?= t('new_service') ?></h3>
    <form method="POST" data-validate>
        <input type="hidden" name="create_service" value="1">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label"><?= t('name') ?>:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label"><?= t('service_type') ?>:</label>
                <select name="type" class="form-control" required>
                    <option value="individual"><?= t('individual') ?></option>
                    <option value="package"><?= t('package_type') ?></option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('description') ?>:</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('price') ?> (R$):</label>
            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= t('create_service') ?></button>
    </form>
</div>

<!-- Lista de serviços -->
<div class="card">
    <h3><?= t('registered_services') ?></h3>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th><?= t('name') ?></th>
                <th><?= t('service_type') ?></th>
                <th><?= t('price') ?></th>
                <th><?= t('description') ?></th>
                <th><?= t('actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= $service->id ?></td>
                    <td><?= htmlspecialchars($service->name) ?></td>
                    <td>
                        <span class="status <?= $service->type === 'package' ? 'status-processing' : 'status-pending' ?>">
                            <?= $service->type === 'package' ? t('package_type') : t('individual') ?>
                        </span>
                    </td>
                    <td>R$ <?= number_format($service->price, 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars(substr($service->description, 0, 50)) ?>...</td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/services/edit/<?= $service->id ?>" 
                           class="btn btn-sm btn-secondary"><?= t('edit') ?></a>
                        
                        <form method="POST" style="display: inline;" 
                              onsubmit="return confirmAction('<?= t('confirm_delete_service') ?>')">
                            <input type="hidden" name="delete_service" value="1">
                            <input type="hidden" name="service_id" value="<?= $service->id ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><?= t('delete') ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>