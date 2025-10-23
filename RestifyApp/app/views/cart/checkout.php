<?php $title = t('checkout') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<h2><?= t('checkout') ?></h2>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div class="card">
        <h3><?= t('order_summary') ?></h3>
        
        <?php 
        $cartService = new CartService();
        $serviceRepo = new ServiceRepository();
        $cartItems = $cartService->getItems();
        $total = $cartService->getTotal();
        ?>
        
        <?php foreach ($cartItems as $serviceId => $quantity): ?>
            <?php $service = $serviceRepo->findById($serviceId); ?>
            <?php if ($service): ?>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= htmlspecialchars($service->name) ?></strong>
                        <br>
                        <small><?= t('quantity') ?>: <?= $quantity ?></small>
                    </div>
                    <div>
                        R$ <?= number_format($service->price * $quantity, 2, ',', '.') ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <div style="display: flex; justify-content: space-between; padding: 1rem 0; font-weight: bold; font-size: 1.2rem;">
            <div><?= t('total') ?>:</div>
            <div>R$ <?= number_format($total, 2, ',', '.') ?></div>
        </div>
    </div>
    
    <div class="card">
        <h3><?= t('checkout') ?></h3>
        
        <p><?= t('checkout_description') ?></p>
        
        <form method="POST">
            <div class="form-group">
                <label>
                    <input type="checkbox" required>
                    <?= t('agree_terms') ?>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?= t('checkout') ?> - R$ <?= number_format($total, 2, ',', '.') ?>
            </button>
        </form>
        
        <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
            <strong><?= t('payment_methods') ?></strong><br>
            • <?= t('pix') ?><br>
            • <?= t('credit_card') ?><br>
            • <?= t('bank_slip') ?><br>
            <em><?= t('payment_details_sent') ?></em>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>