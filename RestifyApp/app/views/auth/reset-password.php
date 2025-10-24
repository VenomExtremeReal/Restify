<?php include '../app/views/layout/header.php'; ?>

<div class="container">
    <div class="main">
        <div class="card" style="max-width: 400px; margin: 2rem auto;">
            <h2><?= t('reset_password') ?></h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($tokenValid) && $tokenValid): ?>
                <form method="POST" id="resetForm">
                    <div class="form-group">
                        <label class="form-label"><?= t('new_password') ?>:</label>
                        <input type="password" name="password" class="form-control" required 
                               minlength="6" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$"
                               title="Senha deve conter ao menos: 1 minúscula, 1 maiúscula e 1 número">
                        <small class="form-text">Mínimo 6 caracteres com letras maiúsculas, minúsculas e números</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= t('confirm_password') ?>:</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?= t('reset_password') ?></button>
                </form>
                
                <script>
                document.getElementById('resetForm').addEventListener('submit', function(e) {
                    const password = document.querySelector('input[name="password"]').value;
                    const confirm = document.querySelector('input[name="confirm_password"]').value;
                    
                    if (password !== confirm) {
                        e.preventDefault();
                        alert('<?= t('passwords_dont_match') ?>');
                        return false;
                    }
                });
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>