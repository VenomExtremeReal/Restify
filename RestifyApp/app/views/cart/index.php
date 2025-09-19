<?php $title = 'Carrinho - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Meu Carrinho</h2>

<?php if (empty($services)): ?>
    <div class="card">
        <p>Seu carrinho está vazio.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">Ver Serviços</a>
    </div>
<?php else: ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['service']->name) ?></strong>
                            <?php if ($item['service']->type === 'package'): ?>
                                <span class="status status-processing">PACOTE</span>
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
                                        onclick="return confirmAction('Remover item do carrinho?')">
                                    Remover
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total:</th>
                    <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        
        <div style="text-align: right; margin-top: 1rem;">
            <a href="<?= BASE_URL ?>/" class="btn btn-secondary">Continuar Comprando</a>
            
            <?php if (isLoggedIn() && !isAdmin()): ?>
                <a href="<?= BASE_URL ?>/cart/checkout" class="btn btn-primary">Finalizar Pedido</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary">Login para Finalizar</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php include '../layout/footer.php'; ?>