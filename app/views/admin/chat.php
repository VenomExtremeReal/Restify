<?php $title = t('chat_center') . ' - Restify'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<h2><?= t('chat_center') ?></h2>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Lista de conversas -->
    <div class="card">
        <h3><?= t('conversations') ?></h3>
        
        <?php if (empty($conversations)): ?>
            <p><?= t('no_conversations') ?></p>
        <?php else: ?>
            <?php foreach ($conversations as $conversation): ?>
                <div style="padding: 1rem; border-bottom: 1px solid #eee; cursor: pointer;"
                     onclick="window.location.href='<?= BASE_URL ?>/admin/chat/<?= $conversation['restaurant_id'] ?? '' ?>'">
                    <strong><?= htmlspecialchars($conversation['restaurant_name'] ?? '') ?></strong>
                    <br>
                    <small style="color: #666;">
                        <?= htmlspecialchars(substr($conversation['message'] ?? '', 0, 50)) ?>...
                    </small>
                    <br>
                    <small style="color: #999;">
                        <?php 
                        if (isset($conversation['created_at'])) {
                            $date = new DateTime($conversation['created_at']);
                            echo $date->format('d/m/Y H:i');
                        }
                        ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Chat ativo -->
    <div class="card">
        <?php if (!empty($messages)): ?>
            <h3><?= t('chat_with') ?> <?= htmlspecialchars($selectedRestaurant->name ?? t('restaurant')) ?></h3>
            
            <div class="chat-container" data-restaurant-id="<?= $restaurantId ?? '' ?>">
                <div class="chat-messages">
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message->sender_type ?>">
                            <div><?= htmlspecialchars($message->message ?? '') ?></div>
                            <div class="message-time">
                                <?php 
                                if ($message->created_at) {
                                    $date = new DateTime($message->created_at);
                                    echo $date->format('d/m/Y H:i');
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <form class="chat-input" method="POST" onsubmit="return sendMessage(this)">
                    <input type="hidden" name="restaurant_id" value="<?= $restaurantId ?? '' ?>">
                    <input type="text" name="message" placeholder="<?= t('type_response') ?>" required>
                    <button type="submit" class="btn btn-primary"><?= t('send') ?></button>
                </form>
            </div>
        <?php else: ?>
            <h3><?= t('select_conversation') ?></h3>
            <p><?= t('click_conversation') ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <h3><?= t('customer_service_tips') ?></h3>
    <ul>
        <li><?= t('tip_1') ?></li>
        <li><?= t('tip_2') ?></li>
        <li><?= t('tip_3') ?></li>
        <li><?= t('tip_4') ?></li>
    </ul>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>