<?php $title = t('restaurants') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('registered_restaurants') ?></h2>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th><?= t('name') ?></th>
                <th><?= t('email') ?></th>
                <th><?= t('whatsapp') ?></th>
                <th><?= t('address') ?></th>
                <th><?= t('created_at') ?></th>
                <th><?= t('actions') ?></th>
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
                           class="btn btn-sm btn-secondary"><?= t('chat') ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3><?= t('statistics') ?></h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="text-align: center;">
            <h4><?= t('total_restaurants') ?></h4>
            <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
                <?= count($restaurants) ?>
            </div>
        </div>
        <div style="text-align: center;">
            <h4><?= t('registrations_today') ?></h4>
            <div style="font-size: 2rem; font-weight: bold; color: #28a745;">
                <?= count(array_filter($restaurants, fn($r) => date('Y-m-d', strtotime($r->created_at)) === date('Y-m-d'))) ?>
            </div>
        </div>
        <div style="text-align: center;">
            <h4><?= t('this_week') ?></h4>
            <div style="font-size: 2rem; font-weight: bold; color: #ffc107;">
                <?= count(array_filter($restaurants, fn($r) => strtotime($r->created_at) >= strtotime('-7 days'))) ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>