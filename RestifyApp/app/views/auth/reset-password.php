<?php include '../app/views/layout/header.php'; ?>

<div class="container">
    <div class="main">
        <div class="card" style="max-width: 400px; margin: 2rem auto;">
            <h2><?= t('reset_password') ?></h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label"><?= t('new_password') ?>:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label"><?= t('confirm_password') ?>:</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?= t('reset_password') ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>