<?php $title = t('page_not_found') . ' - Restify'; ?>
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="card" style="text-align: center; margin: 4rem auto; max-width: 500px;">
    <h1 style="font-size: 4rem; color: #667eea; margin-bottom: 1rem;">404</h1>
    <h2><?= t('page_not_found') ?></h2>
    <p><?= t('page_not_found_desc') ?></p>
    
    <div style="margin-top: 2rem;">
        <a href="<?= BASE_URL ?>/" class="btn btn-primary"><?= t('back_to_home') ?></a>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>