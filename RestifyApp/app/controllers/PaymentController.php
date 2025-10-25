<?php
require_once __DIR__ . '/../services/EfiPaymentService.php';

class PaymentController {
    private $efiService;
    
    public function __construct() {
        $this->efiService = new EfiPaymentService('sandbox');
    }

    public function selectMethod() {
        $orderId = $_GET['order_id'] ?? 1;
        $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
        include '../app/views/payment/select-method.php';
    }

    public function processPix() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
            
            $result = $this->efiService->createPixCharge(
                $order['total_amount'],
                ['name' => $_POST['payer_name'], 'cpf' => $_POST['payer_cpf']],
                "Pedido #$orderId - Restify"
            );
            
            if ($result['success']) $success = $result;
            else $error = 'Erro ao gerar PIX';
        }

        $orderId = $_GET['order_id'] ?? $_POST['order_id'] ?? 1;
        $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
        include '../app/views/payment/pix.php';
    }

    public function processCreditCard() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = [
                'payment_id' => 'CARD_TEST_' . time(),
                'status' => 'approved',
                'amount' => 299.99,
                'installments' => $_POST['installments'] ?? 1
            ];
        }

        $orderId = $_GET['order_id'] ?? $_POST['order_id'] ?? 1;
        $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
        include '../app/views/payment/credit-card.php';
    }

    public function generateBoleto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
            
            $result = $this->efiService->createBillet(
                $order['total_amount'],
                ['name' => $_POST['payer_name'], 'cpf' => $_POST['payer_cpf'], 'email' => $_POST['payer_email']],
                $_POST['due_date'] ?? null
            );
            
            if ($result['success']) $success = $result;
            else $error = 'Erro ao gerar boleto';
        }

        $orderId = $_GET['order_id'] ?? $_POST['order_id'] ?? 1;
        $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
        include '../app/views/payment/boleto.php';
    }

    public function generateCarne() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $installments = $_POST['installments'];
            $order = $this->getOrder($_POST['order_id']) ?: ['id' => $_POST['order_id'], 'total_amount' => 299.99];
            $installmentAmount = $order['total_amount'] / $installments;
            
            $installmentsList = [];
            for ($i = 1; $i <= $installments; $i++) {
                $installmentsList[] = [
                    'installment' => $i,
                    'amount' => $installmentAmount,
                    'due_date' => date('Y-m-d', strtotime('+' . $i . ' months')),
                    'boleto_url' => '#'
                ];
            }
            
            $success = [
                'payment_id' => 'CARNE_TEST_' . time(),
                'total_amount' => $order['total_amount'],
                'installments' => $installments,
                'first_due_date' => date('Y-m-d', strtotime('+30 days')),
                'installments_list' => $installmentsList,
                'carne_url' => '#'
            ];
        }

        $orderId = $_GET['order_id'] ?? $_POST['order_id'] ?? 1;
        $order = $this->getOrder($orderId) ?: ['id' => $orderId, 'total_amount' => 299.99];
        include '../app/views/payment/carne.php';
    }
    
    private function getOrder($orderId) {
        try {
            $database = Database::getInstance();
            $conn = $database->connect();
            $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$orderId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
}
?>