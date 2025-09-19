<?php $title = 'Editar Serviço - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Editar Serviço</h2>

<div class="card">
    <form method="POST" action="<?= BASE_URL ?>/admin/services" data-validate>
        <input type="hidden" name="update_service" value="1">
        <input type="hidden" name="id" value="<?= $service->id ?>">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label">Nome:</label>
                <input type="text" name="name" class="form-control" 
                       value="<?= htmlspecialchars($service->name) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tipo:</label>
                <select name="type" class="form-control" required>
                    <option value="individual" <?= $service->type === 'individual' ? 'selected' : '' ?>>Individual</option>
                    <option value="package" <?= $service->type === 'package' ? 'selected' : '' ?>>Pacote</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Descrição:</label>
            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($service->description) ?></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">Preço (R$):</label>
            <input type="number" name="price" class="form-control" step="0.01" min="0" 
                   value="<?= $service->price ?>" required>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="<?= BASE_URL ?>/admin/services" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php include '../layout/footer.php'; ?>