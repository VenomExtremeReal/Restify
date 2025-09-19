<?php $title = 'Admin Dashboard - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Painel Administrativo</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center;">
        <h3 style="color: #667eea;">Total de Pedidos</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
            <?= count($orders) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #28a745;">Restaurantes</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
            <?= count($restaurants) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #ffc107;">Pendentes</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
            <?= count(array_filter($orders, fn($o) => $o['status'] === 'pending')) ?>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <h3 style="color: #17a2b8;">Em Andamento</h3>
        <div style="font-size: 2rem; font-weight: bold; color: #17a2b8;">
            <?= count(array_filter($orders, fn($o) => $o['status'] === 'processing')) ?>
        </div>
    </div>
</div>

<div class="card">
    <h3>Últimos Pedidos</h3>
    
    <?php if (empty($orders)): ?>
        <p>Nenhum pedido encontrado.</p>
    <?php else: ?>
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
                <?php foreach (array_slice($orders, 0, 10) as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                        <td>
                            <span class="status status-<?= $order['status'] ?>">
                                <?php
                                $statusLabels = [
                                    'pending' => 'Pendente',
                                    'processing' => 'Em Andamento',
                                    'completed' => 'Concluído',
                                    'cancelled' => 'Cancelado'
                                ];
                                echo $statusLabels[$order['status']] ?? $order['status'];
                                ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/chat/<?= $order['restaurant_id'] ?>" 
                               class="btn btn-sm btn-secondary">Chat</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-primary">Ver Todos os Pedidos</a>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Ações Rápidas</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-primary">Gerenciar Pedidos</a>
        <a href="<?= BASE_URL ?>/admin/restaurants" class="btn btn-secondary">Ver Restaurantes</a>
        <a href="<?= BASE_URL ?>/admin/services" class="btn btn-secondary">Gerenciar Serviços</a>
        <a href="<?= BASE_URL ?>/admin/chat" class="btn btn-secondary">Central de Chat</a>
    </div>
</div>

<?php include '../layout/footer.php'; ?>