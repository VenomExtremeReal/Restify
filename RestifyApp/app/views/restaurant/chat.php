<?php $title = 'Chat - Restify'; ?>
<?php include '../layout/header.php'; ?>

<h2>Chat com Suporte</h2>

<div class="card">
    <div class="chat-container">
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
            <input type="text" name="message" placeholder="Digite sua mensagem..." required>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>

<div class="card">
    <h3>Como funciona o suporte?</h3>
    <ul>
        <li>Envie suas dúvidas ou solicitações através do chat</li>
        <li>Nossa equipe responde em até 2 horas no horário comercial</li>
        <li>Você pode acompanhar o progresso dos seus projetos aqui</li>
        <li>Todas as conversas ficam salvas para consulta</li>
    </ul>
    
    <p><strong>Horário de atendimento:</strong> Segunda a Sexta, 8h às 18h</p>
</div>

<?php include '../layout/footer.php'; ?>