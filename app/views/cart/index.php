<?php $title = t('cart') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('cart') ?></h2>

<?php if (empty($services)): ?>
    <div class="card">
        <p><?= t('cart_empty') ?></p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-services"><?= t('services') ?></a>
    </div>
<?php else: ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th><?= t('service_name') ?></th>
                    <th><?= t('price') ?></th>
                    <th><?= t('quantity') ?></th>
                    <th><?= t('subtotal') ?></th>
                    <th><?= t('actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['service']->name) ?></strong>
                            <?php if ($item['service']->type === 'package'): ?>
                                <span class="status status-processing"><?= t('package') ?></span>
                            <?php endif; ?>
                            <br>
                            <small><?= htmlspecialchars($item['service']->description) ?></small>
                        </td>
                        <td>R$ <?= number_format($item['service']->price, 2, ',', '.') ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>R$ <?= number_format($item['service']->price * $item['quantity'], 2, ',', '.') ?></td>
                        <td>
                            <form method="POST" action="<?= BASE_URL ?>/cart/remove" style="display: inline;">
                                <input type="hidden" name="service_id" value="<?= $item['service']->id ?>">
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirmAction('<?= t('confirm_action') ?>')">
                                    <?= t('remove_item') ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3"><?= t('total') ?>:</th>
                    <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        
        <div style="text-align: right; margin-top: 1rem;">
            <a href="<?= BASE_URL ?>/" class="btn btn-secondary"><?= t('continue_shopping') ?></a>
            
            <?php if (isLoggedIn() && !isAdmin()): ?>
                <a href="<?= BASE_URL ?>/cart/checkout" class="btn btn-primary"><?= t('checkout') ?></a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary"><?= t('login') ?></a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>