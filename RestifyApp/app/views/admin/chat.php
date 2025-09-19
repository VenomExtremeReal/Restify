<?php $title = 'Central de Chat - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Central de Chat</h2>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Lista de conversas -->
    <div class="card">
        <h3>Conversas</h3>
        
        <?php if (empty($conversations)): ?>
            <p>Nenhuma conversa encontrada.</p>
        <?php else: ?>
            <?php foreach ($conversations as $conversation): ?>
                <div style="padding: 1rem; border-bottom: 1px solid #eee; cursor: pointer;"
                     onclick="window.location.href='<?= BASE_URL ?>/admin/chat/<?= $conversation['restaurant_id'] ?>'">
                    <strong><?= htmlspecialchars($conversation['restaurant_name']) ?></strong>
                    <br>
                    <small style="color: #666;">
                        <?= htmlspecialchars(substr($conversation['message'], 0, 50)) ?>...
                    </small>
                    <br>
                    <small style="color: #999;">
                        <?= date('d/m/Y H:i', strtotime($conversation['created_at'])) ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Chat ativo -->
    <div class="card">
        <?php if (!empty($messages)): ?>
            <h3>Chat com <?= htmlspecialchars($conversations[0]['restaurant_name'] ?? 'Restaurante') ?></h3>
            
            <div class="chat-container" data-restaurant-id="<?= $restaurantId ?>">
                <div class="chat-messages">
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message->sender_type ?>">
                            <div><?= htmlspecialchars($message->message) ?></div>
                            <div class="message-time">
                                <?= date('d/m/Y H:i', strtotime($message->created_at)) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <form class="chat-input" method="POST" onsubmit="return sendMessage(this)">
                    <input type="hidden" name="restaurant_id" value="<?= $restaurantId ?>">
                    <input type="text" name="message" placeholder="Digite sua resposta..." required>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        <?php else: ?>
            <h3>Selecione uma conversa</h3>
            <p>Clique em uma conversa à esquerda para começar a responder.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <h3>Dicas para o Atendimento</h3>
    <ul>
        <li>Responda sempre de forma cordial e profissional</li>
        <li>Mantenha o cliente informado sobre o progresso dos projetos</li>
        <li>Use linguagem clara e objetiva</li>
        <li>Sempre confirme o entendimento das solicitações</li>
    </ul>
</div>

<?php include '../layout/footer.php'; ?>