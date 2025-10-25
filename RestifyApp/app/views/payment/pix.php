<?php $title = 'Pagamento PIX - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 2rem auto;">
    <h2>📱 Pagamento PIX</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">PIX gerado com sucesso!</div>
        
        <div class="pix-result">
            <div class="qr-code" style="text-align: center; margin: 2rem 0;">
                <h3>QR Code PIX</h3>
                <div class="qr-container" style="background: white; padding: 1rem; border-radius: 10px; display: inline-block; border: 1px solid #ddd; max-width: 100%;">
                    <?php if (isset($success['qr_code_url'])): ?>
                        <iframe src="<?= $success['qr_code_url'] ?>" 
                                width="300" height="400" 
                                frameborder="0" 
                                style="border-radius: 10px; max-width: 100%; height: auto;">
                        </iframe>
                    <?php else: ?>
                        <iframe src="https://pix.sejaefi.com.br/pagar/df0b6157a14aff09f8c3c5d1e03bf03f8a0cbf4d.html" 
                                width="300" height="400" 
                                frameborder="0" 
                                style="border-radius: 10px; max-width: 100%; height: auto;">
                        </iframe>
                    <?php endif; ?>
                </div>
                <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                    Escaneie o QR Code ou clique no link abaixo
                </p>
                
                <div style="margin-top: 1rem;">
                    <a href="<?= isset($success['qr_code_url']) ? $success['qr_code_url'] : 'https://pix.sejaefi.com.br/pagar/df0b6157a14aff09f8c3c5d1e03bf03f8a0cbf4d.html' ?>" 
                       target="_blank" class="btn btn-primary" style="margin: 0.5rem;">
                        📱 Abrir PIX no Celular
                    </a>
                </div>
            </div>
            
            <div class="pix-code">
                <h3>Código PIX Copia e Cola</h3>
                <div class="code-container" style="margin-top: 1rem;">
                    <textarea readonly id="pixCode" class="form-control" rows="3" style="font-size: 0.8rem; font-family: monospace; resize: none;"><?= isset($success['qr_code']) ? htmlspecialchars($success['qr_code']) : '00020126460014BR.GOV.BCB.PIX0124restifyapp.dpo@gmail.com5204000053039865802BR5921LUCAS FERREIRA SANTOS6014BELO HORIZONTE62070503***6304CAC4' ?></textarea>
                    <button onclick="copyPixCode()" class="btn btn-secondary btn-sm" style="margin-top: 0.5rem;">📋 Copiar Código PIX</button>
                </div>
            </div>
            
            <div class="payment-info" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-top: 2rem;">
                <p><strong>Valor:</strong> R$ <?= number_format($success['amount'] ?? $order['total_amount'], 2, ',', '.') ?></p>
                <p><strong>Válido até:</strong> <?= date('d/m/Y H:i', strtotime($success['expires_at'] ?? '+1 hour')) ?></p>
            </div>
        </div>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            
            <div class="form-group">
                <label class="form-label">Nome do Pagador:</label>
                <input type="text" name="payer_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">CPF:</label>
                <input type="text" name="payer_cpf" class="form-control" required maxlength="14" 
                       placeholder="000.000.000-00" onkeyup="maskCPF(this)">
            </div>
            
            <div class="order-summary" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin: 1rem 0;">
                <p><strong>Pedido #<?= $order['id'] ?></strong></p>
                <p><strong>Valor: R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></strong></p>
            </div>
            
            <button type="submit" class="btn btn-primary">Gerar PIX</button>
            <a href="<?= BASE_URL ?>/payment/select?order_id=<?= $order['id'] ?>" class="btn btn-secondary">← Voltar</a>
        </form>
    <?php endif; ?>
</div>

<script>
function copyPixCode() {
    const pixCode = document.getElementById('pixCode');
    pixCode.select();
    pixCode.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        alert('✅ Código PIX copiado!');
    } catch (err) {
        navigator.clipboard.writeText(pixCode.value).then(() => {
            alert('✅ Código PIX copiado!');
        }).catch(() => {
            alert('⚠️ Selecione e copie manualmente.');
        });
    }
}

// As funções de máscara agora estão no app.js global
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>