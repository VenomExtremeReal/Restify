<?php include 'layout/header.php'; ?>

<div class="card">
    <h1>Bem-vindo ao Restify</h1>
    <p>Soluções completas para o seu restaurante crescer no digital</p>
</div>

<h2>Nossos Serviços</h2>

<div class="services-grid">
    <?php foreach ($services as $service): ?>
        <div class="card service-card <?= $service->type === 'package' ? 'package' : '' ?>">
            <?php if ($service->type === 'package'): ?>
                <div class="badge">PACOTE</div>
            <?php endif; ?>
            
            <h3><?= htmlspecialchars($service->name) ?></h3>
            <p><?= htmlspecialchars($service->description) ?></p>
            
            <div class="service-price">
                R$ <?= number_format($service->price, 2, ',', '.') ?>
            </div>
            
            <button class="btn btn-primary" onclick="addToCart(<?= $service->id ?>)">
                Adicionar ao Carrinho
            </button>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h2>Por que escolher o Restify?</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 1rem;">
        <div>
            <h4>🌐 Presença Digital</h4>
            <p>Site profissional e Instagram otimizado para atrair mais clientes</p>
        </div>
        <div>
            <h4>📍 Visibilidade Local</h4>
            <p>Registro no Google Maps com sistema de feedback via QR Code</p>
        </div>
        <div>
            <h4>📱 Cardápio Digital</h4>
            <p>Sistema moderno e responsivo para seus clientes</p>
        </div>
        <div>
            <h4>💰 Economia</h4>
            <p>Pacotes com desconto para máximo custo-benefício</p>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>