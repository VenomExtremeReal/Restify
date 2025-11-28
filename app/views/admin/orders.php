<?php $title = t('manage_orders') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('manage_orders') ?></h2>

<div class="card">
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
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order->id ?></td>
                    <td><?= htmlspecialchars($order->restaurant_name) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                    <td>R$ <?= number_format($order->total_amount, 2, ',', '.') ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto; display: inline;">
                                <option value="pending" <?= $order->status === 'pending' ? 'selected' : '' ?>><?= t('pending') ?></option>
                                <option value="processing" <?= $order->status === 'processing' ? 'selected' : '' ?>><?= t('processing') ?></option>
                                <option value="completed" <?= $order->status === 'completed' ? 'selected' : '' ?>><?= t('completed') ?></option>
                                <option value="cancelled" <?= $order->status === 'cancelled' ? 'selected' : '' ?>><?= t('cancelled') ?></option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/chat/<?= $order->restaurant_id ?>" 
                           class="btn btn-sm btn-secondary"><?= t('chat') ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3><?= t('status_legend') ?></h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <span class="status status-pending"><?= t('pending_desc') ?></span>
        <span class="status status-processing"><?= t('processing_desc') ?></span>
        <span class="status status-completed"><?= t('completed_desc') ?></span>
        <span class="status status-cancelled"><?= t('cancelled_desc') ?></span>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>