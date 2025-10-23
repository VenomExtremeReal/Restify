<?php $title = t('login') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?= t('registration_success') ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['reset'])): ?>
    <div class="alert alert-success">
        <?= t('password_reset_success') ?>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 400px; margin: 2rem auto;">
    <h2><?= t('login') ?></h2>
    
    <form method="POST" data-validate>
        <div class="form-group">
            <label class="form-label"><?= t('login_type') ?>:</label>
            <select name="type" class="form-control" required>
                <option value="restaurant"><?= t('restaurant_type') ?></option>
                <option value="admin"><?= t('admin_type') ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('email') ?>:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('password') ?>:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= t('login') ?></button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        <?= t('dont_have_account') ?> <a href="<?= BASE_URL ?>/auth/register"><?= t('register') ?></a><br>
        <a href="<?= BASE_URL ?>/auth/forgot-password"><?= t('forgot_password') ?></a>
    </p>
    
    <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 5px;">
        <h4><?= t('test_credentials') ?>:</h4>
        <p><strong><?= t('admin_credentials') ?>:</strong><br>
        <?= t('email') ?>: admin@restify.com<br>
        <?= t('password') ?>: admin123</p>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>