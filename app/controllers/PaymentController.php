<?php
/**
 * Controller de pagamentos
 */
class PaymentController {
    private $orderRepo;
    
    public function __construct() {
        if (!isLoggedIn() || isAdmin()) {
            redirect('/auth/login');
        }
        
        $this->orderRepo = new OrderRepository();
    }
    
    public function selectMethod() {
        $orderId = $_GET['order_id'] ?? 0;
        $order = $this->orderRepo->findById($orderId);
        
        if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
            redirect('/restaurant/orders');
        }
        
        include '../app/views/payment/select-method.php';
    }
    
    public function processPix() {
        $orderId = $_GET['order_id'] ?? $_POST['order_id'] ?? 0;
        $order = $this->orderRepo->findById($orderId);
        
        if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
            redirect('/restaurant/orders');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Usar SDK Efí real (sandbox ou production)
                $efiService = new EfiPaymentService('sandbox');
                
                $payer = [
                    'name' => $_POST['payer_name'] ?? $_SESSION['restaurant_name'],
                    'cpf' => preg_replace('/\D/', '', $_POST['payer_cpf'] ?? '00000000000'),
                    'email' => $order->restaurant_email ?? 'cliente@restify.com'
                ];
                
                $result = $efiService->createPixCharge(
                    $order->total_amount,
                    $payer,
                    "Pedido #" . $order->id . " - Restify"
                );
                
                if (isset($result['success']) && $result['success']) {
                    $this->orderRepo->updatePayment(
                        $order->id,
                        $result['payment_id'],
                        'pending',
                        'pix'
                    );
                    
                    $success = $result;
                } else {
                    $error = $result['error'] ?? 'Erro ao gerar PIX';
                }
                
            } catch (Exception $e) {
                $error = 'Erro ao processar pagamento: ' . $e->getMessage();
            }
        }
        
        include '../app/views/payment/pix.php';
    }
    
    public function processCreditCard() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? 0;
            $order = $this->orderRepo->findById($orderId);
            
            if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
                redirect('/restaurant/orders');
            }
            
            // Simular processamento de cartão
            $this->orderRepo->updatePayment(
                $order->id,
                'CARD_' . uniqid(),
                'paid',
                'credit_card'
            );
            
            $this->orderRepo->updateStatus($order->id, 'processing');
            
            redirect('/restaurant/orders?success=payment');
        }
        
        $orderId = $_GET['order_id'] ?? 0;
        $order = $this->orderRepo->findById($orderId);
        
        if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
            redirect('/restaurant/orders');
        }
        
        include '../app/views/payment/credit-card.php';
    }
    
    public function generateBoleto() {
        $orderId = $_GET['order_id'] ?? $_POST['order_id'] ?? 0;
        $order = $this->orderRepo->findById($orderId);
        
        if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
            redirect('/restaurant/orders');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $paymentService = PaymentServiceFactory::create('mock');
                
                $payer = [
                    'name' => $_POST['payer_name'] ?? $_SESSION['restaurant_name'],
                    'email' => $_POST['payer_email'] ?? 'cliente@restify.com'
                ];
                
                $dueDate = $_POST['due_date'] ?? date('Y-m-d', strtotime('+7 days'));
                
                $result = $paymentService->createBillet(
                    $order->total_amount,
                    $payer,
                    $dueDate
                );
                
                if ($result['success']) {
                    $this->orderRepo->updatePayment(
                        $order->id,
                        $result['payment_id'],
                        'pending',
                        'boleto'
                    );
                    
                    $success = $result;
                }
                
            } catch (Exception $e) {
                $error = 'Erro ao gerar boleto: ' . $e->getMessage();
            }
        }
        
        include '../app/views/payment/boleto.php';
    }
    
    public function generateCarne() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? 0;
            $installments = $_POST['installments'] ?? 1;
            $order = $this->orderRepo->findById($orderId);
            
            if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
                redirect('/restaurant/orders');
            }
            
            // Simular geração de carnê
            $this->orderRepo->updatePayment(
                $order->id,
                'CARNE_' . uniqid(),
                'pending',
                'carne'
            );
            
            $carneData = [
                'success' => true,
                'payment_id' => 'CARNE_' . uniqid(),
                'installments' => $installments,
                'amount_per_installment' => $order->total_amount / $installments,
                'total_amount' => $order->total_amount
            ];
            
            include '../app/views/payment/carne.php';
            return;
        }
        
        $orderId = $_GET['order_id'] ?? 0;
        $order = $this->orderRepo->findById($orderId);
        
        if (!$order || $order->restaurant_id != $_SESSION['restaurant_id']) {
            redirect('/restaurant/orders');
        }
        
        include '../app/views/payment/carne.php';
    }
}
?>