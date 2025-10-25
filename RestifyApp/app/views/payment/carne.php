<?php $title = 'Carnê - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 2rem auto;">
    <h2>📄 Carnê</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">Carnê gerado com sucesso!</div>
        
        <div class="carne-result">
            <div class="payment-info" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <p><strong>Valor Total:</strong> R$ <?= number_format($success['total_amount'] ?? $order['total_amount'], 2, ',', '.') ?></p>
                <p><strong>Parcelas:</strong> <?= $success['installments'] ?? 'N/A' ?>x de R$ <?= number_format(($success['total_amount'] ?? $order['total_amount']) / ($success['installments'] ?? 1), 2, ',', '.') ?></p>
                <p><strong>Primeiro Vencimento:</strong> <?= date('d/m/Y', strtotime($success['first_due_date'] ?? '+30 days')) ?></p>
            </div>
            
            <?php if (isset($success['installments_list'])): ?>
                <div class="installments-list">
                    <h3>Parcelas do Carnê</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Parcela</th>
                                    <th>Vencimento</th>
                                    <th>Valor</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($success['installments_list'] as $index => $installment): ?>
                                    <tr>
                                        <td><?= $index + 1 ?>/<?= count($success['installments_list']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($installment['due_date'])) ?></td>
                                        <td>R$ <?= number_format($installment['amount'], 2, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= $installment['boleto_url'] ?? '#' ?>" target="_blank" class="btn btn-sm btn-primary">
                                                Ver Boleto
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success['carne_url'])): ?>
                <div style="text-align: center; margin: 2rem 0;">
                    <a href="<?= $success['carne_url'] ?>" target="_blank" class="btn btn-primary">
                        📄 Baixar Carnê Completo
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            
            <div class="form-group">
                <label class="form-label">Número de Parcelas:</label>
                <select name="installments" class="form-control" required onchange="updateInstallmentPreview()">
                    <?php 
                    $amount = $order['total_amount'];
                    for($i = 2; $i <= 12; $i++): 
                        $installmentValue = $amount / $i;
                    ?>
                        <option value="<?= $i ?>"><?= $i ?>x de R$ <?= number_format($installmentValue, 2, ',', '.') ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Data do Primeiro Vencimento:</label>
                <input type="date" name="first_due_date" class="form-control" 
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                       value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                <small class="form-text">Padrão: 30 dias a partir de hoje</small>
            </div>
            
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
            
            <div class="order-summary" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin: 1rem 0;">
                <p><strong>Pedido #<?= $order['id'] ?></strong></p>
                <p><strong>Valor Total: R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></strong></p>
                <div id="installmentPreview"></div>
            </div>
            
            <button type="submit" class="btn btn-primary">Gerar Carnê</button>
            <a href="<?= BASE_URL ?>/payment/select?order_id=<?= $order['id'] ?>" class="btn btn-secondary">← Voltar</a>
        </form>
    <?php endif; ?>
</div>

<script>
// As funções de máscara agora estão no app.js global

function updateInstallmentPreview() {
    const select = document.querySelector('select[name="installments"]');
    const preview = document.getElementById('installmentPreview');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption) {
        const text = selectedOption.text;
        preview.innerHTML = '<p><strong>Parcelamento:</strong> ' + text + '</p>';
    }
}

// Inicializar preview
document.addEventListener('DOMContentLoaded', function() {
    updateInstallmentPreview();
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>