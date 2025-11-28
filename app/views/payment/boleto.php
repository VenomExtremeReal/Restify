<?php $title = 'Boleto Bancário - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 2rem auto;">
    <h2>🧾 Boleto Bancário</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success">Boleto gerado com sucesso!</div>
        
        <div class="boleto-result">
            <div class="payment-info" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <p><strong>Valor:</strong> R$ <?= number_format($success['amount'] ?? $order->total_amount, 2, ',', '.') ?></p>
                <p><strong>Vencimento:</strong> <?= date('d/m/Y', strtotime($success['due_date'] ?? '+7 days')) ?></p>
                <p><strong>Código de Barras:</strong> <?= $success['barcode'] ?? 'N/A' ?></p>
            </div>
            
            <?php if (isset($success['boleto_url'])): ?>
                <div style="text-align: center; margin: 2rem 0;">
                    <a href="<?= $success['boleto_url'] ?>" target="_blank" class="btn btn-primary">
                        📄 Visualizar/Imprimir Boleto
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success['linha_digitavel'])): ?>
                <div class="linha-digitavel">
                    <h3>Linha Digitável</h3>
                    <div class="code-container">
                        <input type="text" value="<?= $success['linha_digitavel'] ?>" readonly id="linhaDigitavel" class="form-control">
                        <button onclick="copyLinhaDigitavel()" class="btn btn-secondary btn-sm">Copiar</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?= $order->id ?>">
            
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
            
            <div class="form-group">
                <label class="form-label">Endereço Completo:</label>
                <textarea name="payer_address" class="form-control" required rows="3" 
                          placeholder="Rua, número, bairro, cidade, CEP"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Data de Vencimento:</label>
                <input type="date" name="due_date" class="form-control" 
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                       value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                <small class="form-text">Padrão: 7 dias a partir de hoje</small>
            </div>
            
            <div class="order-summary" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin: 1rem 0;">
                <p><strong>Pedido #<?= $order->id ?></strong></p>
                <p><strong>Valor: R$ <?= number_format($order->total_amount, 2, ',', '.') ?></strong></p>
            </div>
            
            <button type="submit" class="btn btn-primary">Gerar Boleto</button>
            <a href="<?= BASE_URL ?>/payment/select?order_id=<?= $order->id ?>" class="btn btn-secondary">← Voltar</a>
        </form>
    <?php endif; ?>
</div>

<script>
function copyLinhaDigitavel() {
    const linha = document.getElementById('linhaDigitavel');
    linha.select();
    document.execCommand('copy');
    alert('Linha digitável copiada!');
}

// As funções de máscara agora estão no app.js global
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>