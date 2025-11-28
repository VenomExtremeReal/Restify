<?php $title = t('login') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?= t('registration_success') ?>
    </div>
<?php endif; ?>



<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card auth-page" style="max-width: 400px; margin: 2rem auto;">
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
            <div style="position: relative;">
                <input type="password" name="password" class="form-control" required>
                <button type="button" onclick="togglePassword(this)" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #666; font-size: 1.2rem;">
                    👁️
                </button>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= t('login') ?></button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        <?= t('dont_have_account') ?> <a href="<?= BASE_URL ?>/auth/register"><?= t('register') ?></a>
    </p>
    

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>