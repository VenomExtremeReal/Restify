<?php $title = 'Pagamento Cartão de Crédito - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 2rem auto;">
    <h2>💳 Pagamento Cartão de Crédito</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success">Pagamento processado com sucesso!</div>
        
        <div class="payment-result">
            <div class="payment-info" style="background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                <p><strong>Status:</strong> <?= $success['status'] ?? 'Aprovado' ?></p>
                <p><strong>ID da Transação:</strong> <?= $success['transaction_id'] ?? 'N/A' ?></p>
                <p><strong>Valor:</strong> R$ <?= number_format($success['amount'] ?? $order->total_amount, 2, ',', '.') ?></p>
                <?php if (isset($success['installments']) && $success['installments'] > 1): ?>
                    <p><strong>Parcelas:</strong> <?= $success['installments'] ?>x de R$ <?= number_format(($success['amount'] ?? $order->total_amount) / $success['installments'], 2, ',', '.') ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <form method="POST" id="creditCardForm">
            <input type="hidden" name="order_id" value="<?= $order->id ?>">
            
            <h3>Dados do Cartão</h3>
            <div class="form-group">
                <label class="form-label">Número do Cartão:</label>
                <input type="text" name="card_number" class="form-control" required maxlength="19" 
                       placeholder="0000 0000 0000 0000" onkeyup="maskCardNumber(this)">
            </div>
            
            <div class="form-group">
                <label class="form-label">Nome no Cartão:</label>
                <input type="text" name="card_holder" class="form-control" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Mês:</label>
                    <select name="expiry_month" class="form-control" required>
                        <option value="">Mês</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= sprintf('%02d', $i) ?>"><?= sprintf('%02d', $i) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ano:</label>
                    <select name="expiry_year" class="form-control" required>
                        <option value="">Ano</option>
                        <?php for($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">CVV:</label>
                    <input type="text" name="cvv" class="form-control" required maxlength="4" placeholder="000">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Parcelas:</label>
                <select name="installments" class="form-control" required onchange="updateInstallmentInfo()">
                    <?php 
                    $amount = $order->total_amount;
                    for($i = 1; $i <= 12; $i++): 
                        $installmentValue = $amount / $i;
                    ?>
                        <option value="<?= $i ?>"><?= $i ?>x de R$ <?= number_format($installmentValue, 2, ',', '.') ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <h3>Dados do Pagador</h3>
            <div class="form-group">
                <label class="form-label">Nome Completo:</label>
                <input type="text" name="payer_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">CPF:</label>
                <input type="text" name="payer_cpf" class="form-control" required maxlength="14" 
                       placeholder="000.000.000-00" onkeyup="maskCPF(this)">
            </div>
            
            <div class="form-group">
                <label class="form-label">E-mail:</label>
                <input type="email" name="payer_email" class="form-control" required>
            </div>
            
            <div class="order-summary" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin: 1rem 0;">
                <p><strong>Pedido #<?= $order->id ?></strong></p>
                <p><strong>Valor Total: R$ <?= number_format($order->total_amount, 2, ',', '.') ?></strong></p>
            </div>
            
            <button type="submit" class="btn btn-primary">Processar Pagamento</button>
            <a href="<?= BASE_URL ?>/payment/select?order_id=<?= $order->id ?>" class="btn btn-secondary">← Voltar</a>
        </form>
    <?php endif; ?>
</div>

<script>
// As funções de máscara agora estão no app.js global
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>