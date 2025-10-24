<?php $title = t('chat') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('chat_with_support') ?></h2>

<div class="card">
    <div class="chat-container">
        <div class="chat-messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message->sender_type ?>">
                    <div><?= htmlspecialchars($message->message ?? '') ?></div>
                    <div class="message-time">
                        <?= $message->created_at ? date('d/m/Y H:i', strtotime($message->created_at)) : '' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <form class="chat-input" method="POST" onsubmit="return sendMessage(this)">
            <input type="text" name="message" placeholder="<?= t('type_message') ?>" required>
            <button type="submit" class="btn btn-primary"><?= t('send') ?></button>
        </form>
    </div>
</div>

<div class="card">
    <h3><?= t('how_support_works') ?></h3>
    <ul>
        <li><?= t('support_step_1') ?></li>
        <li><?= t('support_step_2') ?></li>
        <li><?= t('support_step_3') ?></li>
        <li><?= t('support_step_4') ?></li>
    </ul>
    
    <p><strong><?= t('business_hours') ?></strong> <?= t('business_hours_time') ?></p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>