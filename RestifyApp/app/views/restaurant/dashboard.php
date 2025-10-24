<?php $title = t('restaurant_dashboard') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('restaurant_dashboard') ?> - <?= htmlspecialchars($_SESSION['restaurant_name']) ?></h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center;">
        <h3 style="color: #667eea;"><?= t('total_orders') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
            <?= count($orders) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #28a745;"><?= t('completed') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
            <?= count(array_filter($orders, fn($o) => $o->status === 'completed')) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #ffc107;"><?= t('processing') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
            <?= count(array_filter($orders, fn($o) => $o->status === 'processing')) ?>
        </div>
    </div>
</div>

<div class="card">
    <h3><?= t('recent_orders') ?></h3>
    
    <?php if (empty($orders)): ?>
        <p><?= t('no_orders') ?></p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary"><?= t('services') ?></a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= t('order_date') ?></th>
                    <th><?= t('total') ?></th>
                    <th><?= t('status') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                    <tr>
                        <td>#<?= $order->id ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                        <td>R$ <?= number_format($order->total_amount, 2, ',', '.') ?></td>
                        <td>
                            <span class="status status-<?= $order->status ?>">
                                <?php
                                $statusLabels = [
                                    'pending' => t('pending'),
                                    'processing' => t('processing'),
                                    'completed' => t('completed'),
                                    'cancelled' => t('cancelled')
                                ];
                                echo $statusLabels[$order->status] ?? $order->status;
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (count($orders) > 5): ?>
            <a href="<?= BASE_URL ?>/restaurant/orders" class="btn btn-secondary"><?= t('my_orders') ?></a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3><?= t('actions') ?></h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/" class="btn btn-primary"><?= t('services') ?></a>
        <a href="<?= BASE_URL ?>/restaurant/orders" class="btn btn-secondary"><?= t('my_orders') ?></a>
        <a href="<?= BASE_URL ?>/restaurant/chat" class="btn btn-secondary"><?= t('chat_with_support') ?></a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>