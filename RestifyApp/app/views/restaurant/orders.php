<?php $title = t('my_orders') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?= t('order_placed_success') ?>
    </div>
<?php endif; ?>

<h2><?= t('my_orders') ?></h2>

<?php if (empty($orders)): ?>
    <div class="card">
        <p><?= t('no_orders') ?></p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary"><?= t('services') ?></a>
    </div>
<?php else: ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= t('order_date') ?></th>
                    <th><?= t('total') ?></th>
                    <th><?= t('status') ?></th>
                    <th><?= t('actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
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
                        <td>
                            <?php if ($order->status === 'pending' || $order->status === 'processing'): ?>
                                <a href="<?= BASE_URL ?>/restaurant/chat" class="btn btn-sm btn-secondary">
                                    <?= t('chat') ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card">
        <h3><?= t('important_info') ?></h3>
        <ul>
            <li><strong><?= t('pending') ?>:</strong> <?= t('pending_info') ?></li>
            <li><strong><?= t('processing') ?>:</strong> <?= t('processing_info') ?></li>
            <li><strong><?= t('completed') ?>:</strong> <?= t('completed_info') ?></li>
        </ul>
        <p><?= t('questions_chat') ?> <a href="<?= BASE_URL ?>/restaurant/chat"><?= t('chat') ?></a>.</p>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>