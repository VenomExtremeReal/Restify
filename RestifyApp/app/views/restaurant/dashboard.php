<?php $title = 'Dashboard - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Dashboard - <?= htmlspecialchars($_SESSION['restaurant_name']) ?></h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center;">
        <h3 style="color: #667eea;">Pedidos Realizados</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
            <?= count($orders) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #28a745;">Pedidos Concluídos</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
            <?= count(array_filter($orders, fn($o) => $o->status === 'completed')) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #ffc107;">Em Andamento</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
            <?= count(array_filter($orders, fn($o) => $o->status === 'processing')) ?>
        </div>
    </div>
</div>

<div class="card">
    <h3>Últimos Pedidos</h3>
    
    <?php if (empty($orders)): ?>
        <p>Nenhum pedido realizado ainda.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">Ver Serviços</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status</th>
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
                                    'pending' => 'Pendente',
                                    'processing' => 'Em Andamento',
                                    'completed' => 'Concluído',
                                    'cancelled' => 'Cancelado'
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
            <a href="<?= BASE_URL ?>/restaurant/orders" class="btn btn-secondary">Ver Todos os Pedidos</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Ações Rápidas</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">Contratar Serviços</a>
        <a href="<?= BASE_URL ?>/restaurant/orders" class="btn btn-secondary">Ver Pedidos</a>
        <a href="<?= BASE_URL ?>/restaurant/chat" class="btn btn-secondary">Chat com Suporte</a>
    </div>
</div>

<?php include '../layout/footer.php'; ?>