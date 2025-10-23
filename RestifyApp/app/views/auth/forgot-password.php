<?php include '../app/views/layout/header.php'; ?>

<div class="container">
    <div class="main">
        <div class="card" style="max-width: 400px; margin: 2rem auto;">
            <h2><?= t('forgot_password') ?></h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label"><?= t('email') ?>:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary"><?= t('send_reset_link') ?></button>
                <a href="/auth/login" class="btn btn-secondary"><?= t('back') ?></a>
            </form>
        </div>
    </div>
</div>

<?php include '../app/views/layout/footer.php'; ?>