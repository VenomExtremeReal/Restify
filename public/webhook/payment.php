<?php
/**
 * Webhook para receber notificações de pagamento do RESTIFY
 */

require_once '../../config/config.php';

// Log das requisições recebidas
function logWebhook($data) {
    $logFile = '../logs/payment_webhook.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logEntry = date('Y-m-d H:i:s') . ' - ' . json_encode($data) . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

try {
    // Verificar se é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed');
    }

    // Obter dados do webhook
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Log da requisição
    logWebhook([
        'headers' => getallheaders(),
        'body' => $data,
        'timestamp' => time()
    ]);

    if (!$data || !isset($data['payment_id'])) {
        http_response_code(400);
        exit('Invalid data');
    }

    // Processar notificação de pagamento
    $paymentId = $data['payment_id'];
    $status = $data['status'] ?? 'unknown';
    $method = $data['method'] ?? 'unknown';
    
    // Atualizar status do pedido no banco de dados
    updateOrderPaymentStatus($paymentId, $status, $method, $data);

    // Resposta de sucesso
    http_response_code(200);
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    logWebhook(['error' => $e->getMessage()]);
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

/**
 * Atualizar status do pagamento no banco
 */
function updateOrderPaymentStatus($paymentId, $status, $method, $webhookData) {
    try {
        $database = new Database();
        $conn = $database->connect();
        
        // Mapear status do webhook para status do pedido
        $orderStatus = mapPaymentStatusToOrderStatus($status);
        $paymentStatus = mapPaymentStatus($status);
        
        // Atualizar tabela de pedidos
        $stmt = $conn->prepare("
            UPDATE orders 
            SET status = ?, payment_status = ?, payment_method = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE payment_id = ?
        ");
        $stmt->execute([$orderStatus, $paymentStatus, $method, $paymentId]);
        
        // Log da atualização
        logWebhook([
            'action' => 'order_updated',
            'payment_id' => $paymentId,
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'method' => $method
        ]);
        
    } catch (Exception $e) {
        logWebhook(['error_updating_order' => $e->getMessage()]);
    }
}

/**
 * Mapear status de pagamento para status do pedido
 */
function mapPaymentStatusToOrderStatus($paymentStatus) {
    switch (strtolower($paymentStatus)) {
        case 'paid':
        case 'approved':
        case 'confirmed':
            return 'processing';
        case 'cancelled':
        case 'failed':
        case 'rejected':
            return 'cancelled';
        case 'pending':
        case 'waiting':
        default:
            return 'pending';
    }
}

/**
 * Mapear status de pagamento
 */
function mapPaymentStatus($status) {
    switch (strtolower($status)) {
        case 'paid':
        case 'approved':
        case 'confirmed':
            return 'paid';
        case 'cancelled':
        case 'failed':
        case 'rejected':
            return 'failed';
        case 'pending':
        case 'waiting':
        default:
            return 'pending';
    }
}
?>