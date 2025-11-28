<?php include __DIR__ . '/layout/header.php'; ?>

<div class="card">
    <h1><?= t('welcome') ?> ao Restify</h1>
    <p><?= t('complete_solutions') ?></p>
</div>

<h2><?= t('our_services') ?></h2>

<div class="services-grid">
    <?php foreach ($services as $service): ?>
        <div class="card service-card <?= $service->type === 'package' ? 'package' : '' ?>">
            <?php if ($service->type === 'package'): ?>
                <div class="badge"><?= t('package') ?></div>
            <?php endif; ?>
            
            <h3><?= htmlspecialchars($service->name) ?></h3>
            <p><?= htmlspecialchars($service->description) ?></p>
            
            <div class="service-price">
                R$ <?= number_format($service->price, 2, ',', '.') ?>
            </div>
            
            <button class="btn btn-primary" onclick="addToCart(<?= $service->id ?>)">
                <?= t('add_to_cart') ?>
            </button>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h2><?= t('why_choose_restify') ?></h2>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1rem;">
        <div>
            <h4><?= t('digital_presence') ?></h4>
            <p><?= t('digital_presence_desc') ?></p>
        </div>
        <div>
            <h4><?= t('local_visibility') ?></h4>
            <p><?= t('local_visibility_desc') ?></p>
        </div>
        <div>
            <h4><?= t('digital_menu') ?></h4>
            <p><?= t('digital_menu_desc') ?></p>
        </div>
        <div>
            <h4><?= t('economy') ?></h4>
            <p><?= t('economy_desc') ?></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>