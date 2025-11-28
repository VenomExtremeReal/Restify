<?php $title = 'Escolher Forma de Pagamento - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 2rem auto;">
    <h2><?= t('choose_payment_method') ?></h2>
    
    <div class="order-summary" style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
        <h3>Resumo do Pedido #<?= $order->id ?></h3>
        <p><strong>Valor Total: R$ <?= number_format($order->total_amount, 2, ',', '.') ?></strong></p>
    </div>

    <div class="payment-methods">
        <div class="payment-option">
            <a href="<?= BASE_URL ?>/payment/pix?order_id=<?= $order->id ?>" class="payment-btn">
                <div class="payment-info">
                    <h3>PIX</h3>
                    <p>Pagamento instantâneo via QR Code</p>
                </div>
            </a>
        </div>

        <div class="payment-option">
            <a href="<?= BASE_URL ?>/payment/credit-card?order_id=<?= $order->id ?>" class="payment-btn">
                <div class="payment-info">
                    <h3>Cartão de Crédito</h3>
                    <p>Parcelamento em até 12x</p>
                </div>
            </a>
        </div>

        <div class="payment-option">
            <a href="<?= BASE_URL ?>/payment/boleto?order_id=<?= $order->id ?>" class="payment-btn">
                <div class="payment-info">
                    <h3>Boleto Bancário</h3>
                    <p>Vencimento em 7 dias</p>
                </div>
            </a>
        </div>

        <div class="payment-option">
            <a href="<?= BASE_URL ?>/payment/carne?order_id=<?= $order->id ?>" class="payment-btn">
                <div class="payment-info">
                    <h3>Carnê</h3>
                    <p>Parcelamento via boletos</p>
                </div>
            </a>
        </div>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="<?= BASE_URL ?>/cart" class="btn btn-secondary">← Voltar ao Carrinho</a>
    </div>
</div>

<style>
.payment-methods {
    display: grid;
    gap: 1rem;
}

.payment-option {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s;
}

.payment-option:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.payment-btn {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    width: 100%;
}



.payment-info h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
}

.payment-info p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

body.dark-theme .payment-option {
    border-color: #4a5568;
}

body.dark-theme .payment-info h3 {
    color: #e2e8f0;
}

body.dark-theme .payment-info p {
    color: #cbd5e0;
}

/* Melhorias para PIX mobile */
.qr-code {
    margin: 1.5rem 0;
}

.pix-result {
    margin-top: 1rem;
}

.payment-info {
    margin: 1rem 0;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

body.dark-theme .payment-info {
    background: #4a5568;
    color: #e2e8f0;
}

@media (max-width: 768px) {
    .qr-code {
        margin: 1rem 0;
    }
    
    .qr-code h3 {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .qr-code iframe {
        width: 100% !important;
        max-width: 300px !important;
        height: 380px !important;
    }
    
    .code-container {
        margin-top: 1rem;
    }
    
    .code-container textarea {
        font-size: 0.75rem;
        padding: 0.8rem;
    }
}

@media (max-width: 480px) {
    .qr-code iframe {
        width: 100% !important;
        max-width: 280px !important;
        height: 350px !important;
    }
    
    .code-container textarea {
        font-size: 0.7rem;
        height: 70px;
        padding: 0.6rem;
    }
    
    .payment-info {
        padding: 0.8rem;
        margin: 0.8rem 0;
    }
    
    .payment-info p {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
}

/* Responsividade para pagamentos */
@media (max-width: 768px) {
    .payment-methods {
        gap: 0.8rem;
    }
    
    .payment-btn {
        padding: 1.2rem;
    }
}

@media (max-width: 480px) {
    .payment-btn {
        text-align: center;
        padding: 1.5rem 1rem;
    }
    
    .payment-info h3 {
        font-size: 1.1rem;
    }
    
    .payment-info p {
        font-size: 0.85rem;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>