<?php $title = 'Meus Pedidos - Restify'; ?>
<?php include '../layout/header.php'; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        Pedido realizado com sucesso! Nossa equipe entrará em contato em breve.
    </div>
<?php endif; ?>

<h2>Meus Pedidos</h2>

<?php if (empty($orders)): ?>
    <div class="card">
        <p>Você ainda não fez nenhum pedido.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">Ver Serviços</a>
    </div>
<?php else: ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
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
                                    'pending' => 'Pendente',
                                    'processing' => 'Em Andamento',
                                    'completed' => 'Concluído',
                                    'cancelled' => 'Cancelado'
                                ];
                                echo $statusLabels[$order->status] ?? $order->status;
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($order->status === 'pending' || $order->status === 'processing'): ?>
                                <a href="<?= BASE_URL ?>/restaurant/chat" class="btn btn-sm btn-secondary">
                                    Conversar
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card">
        <h3>Informações Importantes</h3>
        <ul>
            <li><strong>Pendente:</strong> Pedido recebido, aguardando processamento</li>
            <li><strong>Em Andamento:</strong> Nossa equipe está trabalhando no seu projeto</li>
            <li><strong>Concluído:</strong> Serviços entregues com sucesso</li>
        </ul>
        <p>Para dúvidas ou acompanhamento, use nosso <a href="<?= BASE_URL ?>/restaurant/chat">sistema de chat</a>.</p>
    </div>
<?php endif; ?>

<?php include '../layout/footer.php'; ?>