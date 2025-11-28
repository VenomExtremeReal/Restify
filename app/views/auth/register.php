<?php $title = t('register') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card auth-page" style="max-width: 500px; margin: 2rem auto;">
    <h2><?= t('restaurant_registration') ?></h2>
    
    <form method="POST" data-validate>
        <div class="form-group">
            <label class="form-label"><?= t('name') ?>:</label>
            <input type="text" name="name" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('email') ?>:</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('whatsapp') ?>:</label>
            <input type="text" name="whatsapp" class="form-control" required
                   placeholder="(11) 99999-9999" maxlength="15"
                   onkeyup="maskPhone(this)"
                   value="<?= htmlspecialchars($_POST['whatsapp'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label"><?= t('address') ?>:</label>
            <textarea name="address" class="form-control" required rows="3"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
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
        
        <button type="submit" class="btn btn-primary"><?= t('register') ?></button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        <?= t('already_have_account') ?> <a href="<?= BASE_URL ?>/auth/login"><?= t('login') ?></a>
    </p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>