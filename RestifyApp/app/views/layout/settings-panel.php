<div class="settings-controls">
    <div class="settings-group">
        <label class="settings-label"><?= t('language') ?></label>
        <select class="settings-select" onchange="changeLanguage(this.value)">
            <option value="pt" <?= ($_SESSION['language'] ?? 'pt') === 'pt' ? 'selected' : '' ?>><?= t('portuguese') ?></option>
            <option value="en" <?= ($_SESSION['language'] ?? 'pt') === 'en' ? 'selected' : '' ?>><?= t('english') ?></option>
            <option value="es" <?= ($_SESSION['language'] ?? 'pt') === 'es' ? 'selected' : '' ?>><?= t('spanish') ?></option>
        </select>
    </div>
    
    <div class="settings-group">
        <label class="settings-label"><?= t('theme_light') ?> / <?= t('theme_dark') ?></label>
        <div class="theme-toggle">
            <button class="theme-btn" data-theme="light" onclick="toggleTheme('light')"><?= t('theme_light') ?></button>
            <button class="theme-btn" data-theme="dark" onclick="toggleTheme('dark')"><?= t('theme_dark') ?></button>
        </div>
    </div>
    
    <?php if (isset($_SESSION['admin']) || isset($_SESSION['restaurant_id'])): ?>
    <div class="export-buttons">
        <a href="<?= BASE_URL ?>/export/orders" class="export-btn"><?= t('export_csv') ?></a>
        <?php if (isset($_SESSION['admin'])): ?>
        <a href="<?= BASE_URL ?>/export/restaurants" class="export-btn"><?= t('restaurants') ?></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>