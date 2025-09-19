<?php $title = 'Cadastro - Restify'; ?>
<?php include '../layout/header.php'; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 500px; margin: 2rem auto;">
    <h2>Cadastro de Restaurante</h2>
    
    <form method="POST" data-validate>
        <div class="form-group">
            <label class="form-label">Nome do Restaurante:</label>
            <input type="text" name="name" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label">WhatsApp:</label>
            <input type="text" name="whatsapp" class="form-control" required
                   placeholder="(11) 99999-9999"
                   value="<?= htmlspecialchars($_POST['whatsapp'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label">Endereço:</label>
            <textarea name="address" class="form-control" required rows="3"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">Senha:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        Já tem conta? <a href="<?= BASE_URL ?>/auth/login">Faça login</a>
    </p>
</div>

<?php include '../layout/footer.php'; ?>