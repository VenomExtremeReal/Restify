<?php $title = t('admin_dashboard') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('admin_dashboard') ?></h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center;">
        <h3 style="color: #667eea;"><?= t('total_orders') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
            <?= count($orders) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #28a745;"><?= t('restaurants') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
            <?= count($restaurants) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #ffc107;"><?= t('pending_orders') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
            <?= count(array_filter($orders, fn($o) => $o->status === 'pending')) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #17a2b8;"><?= t('processing') ?></h3>
        <div style="font-size: 2rem; font-weight: bold; color: #17a2b8;">
            <?= count(array_filter($orders, fn($o) => $o->status === 'processing')) ?>
        </div>
    </div>
</div>

<div class="card">
    <h3><?= t('recent_orders') ?></h3>
    
    <?php if (empty($orders)): ?>
        <p><?= t('no_orders') ?></p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= t('restaurants') ?></th>
                    <th><?= t('order_date') ?></th>
                    <th><?= t('total') ?></th>
                    <th><?= t('status') ?></th>
                    <th><?= t('actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($orders, 0, 10) as $order): ?>
                    <tr>
                        <td>#<?= $order->id ?></td>
                        <td><?= htmlspecialchars($order->restaurant_name) ?></td>
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
                        <td>
                            <a href="<?= BASE_URL ?>/admin/chat/<?= $order->restaurant_id ?>" 
                               class="btn btn-sm btn-secondary"><?= t('chat') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-primary"><?= t('view_details') ?></a>
    <?php endif; ?>
</div>

<div class="card">
    <h3><?= t('actions') ?></h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-primary"><?= t('order_management') ?></a>
        <a href="<?= BASE_URL ?>/admin/restaurants" class="btn btn-secondary"><?= t('restaurant_management') ?></a>
        <a href="<?= BASE_URL ?>/admin/services" class="btn btn-secondary"><?= t('service_management') ?></a>
        <a href="<?= BASE_URL ?>/admin/chat" class="btn btn-secondary"><?= t('chat_support') ?></a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>