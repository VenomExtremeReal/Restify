<?php $title = 'Finalizar Pedido - Restify'; ?>
<?php include '../layout/header.php'; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<h2>Finalizar Pedido</h2>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div class="card">
        <h3>Resumo do Pedido</h3>
        
        <?php 
        $cartService = new CartService();
        $serviceRepo = new ServiceRepository();
        $cartItems = $cartService->getItems();
        $total = $cartService->getTotal();
        ?>
        
        <?php foreach ($cartItems as $serviceId => $quantity): ?>
            <?php $service = $serviceRepo->findById($serviceId); ?>
            <?php if ($service): ?>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= htmlspecialchars($service->name) ?></strong>
                        <br>
                        <small>Quantidade: <?= $quantity ?></small>
                    </div>
                    <div>
                        R$ <?= number_format($service->price * $quantity, 2, ',', '.') ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <div style="display: flex; justify-content: space-between; padding: 1rem 0; font-weight: bold; font-size: 1.2rem;">
            <div>Total:</div>
            <div>R$ <?= number_format($total, 2, ',', '.') ?></div>
        </div>
    </div>
    
    <div class="card">
        <h3>Confirmar Pedido</h3>
        
        <p>Ao confirmar este pedido, nossa equipe entrará em contato com você em até 24 horas para dar início aos serviços contratados.</p>
        
        <form method="POST">
            <div class="form-group">
                <label>
                    <input type="checkbox" required>
                    Concordo com os termos de serviço
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Confirmar Pedido - R$ <?= number_format($total, 2, ',', '.') ?>
            </button>
        </form>
        
        <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
            <strong>Formas de pagamento:</strong><br>
            • PIX<br>
            • Cartão de crédito<br>
            • Boleto bancário<br>
            <em>Detalhes serão enviados após a confirmação</em>
        </p>
    </div>
</div>

<?php include '../layout/footer.php'; ?>