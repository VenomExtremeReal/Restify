<?php $title = 'Gerenciar Pedidos - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Gerenciar Pedidos</h2>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Restaurante</th>
                <th>Data</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto; display: inline;">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pendente</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Concluído</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/chat/<?= $order['restaurant_id'] ?>" 
                           class="btn btn-sm btn-secondary">Chat</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Legenda de Status</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <span class="status status-pending">Pendente - Aguardando processamento</span>
        <span class="status status-processing">Em Andamento - Equipe trabalhando</span>
        <span class="status status-completed">Concluído - Serviços entregues</span>
        <span class="status status-cancelled">Cancelado - Pedido cancelado</span>
    </div>
</div>

<?php include '../layout/footer.php'; ?>