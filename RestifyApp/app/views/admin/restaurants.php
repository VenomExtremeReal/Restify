<?php $title = 'Restaurantes - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Restaurantes Cadastrados</h2>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>WhatsApp</th>
                <th>Endereço</th>
                <th>Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($restaurants as $restaurant): ?>
                <tr>
                    <td><?= $restaurant->id ?></td>
                    <td><?= htmlspecialchars($restaurant->name) ?></td>
                    <td><?= htmlspecialchars($restaurant->email) ?></td>
                    <td><?= htmlspecialchars($restaurant->whatsapp) ?></td>
                    <td><?= htmlspecialchars(substr($restaurant->address, 0, 50)) ?>...</td>
                    <td><?= date('d/m/Y', strtotime($restaurant->created_at)) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/chat/<?= $restaurant->id ?>" 
                           class="btn btn-sm btn-secondary">Chat</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Estatísticas</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="text-align: center;">
            <h4>Total de Restaurantes</h4>
            <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
                <?= count($restaurants) ?>
            </div>
        </div>
        <div style="text-align: center;">
            <h4>Cadastros Hoje</h4>
            <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
                <?= count(array_filter($restaurants, fn($r) => date('Y-m-d', strtotime($r->created_at)) === date('Y-m-d'))) ?>
            </div>
        </div>
        <div style="text-align: center;">
            <h4>Esta Semana</h4>
            <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
                <?= count(array_filter($restaurants, fn($r) => strtotime($r->created_at) >= strtotime('-7 days'))) ?>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>