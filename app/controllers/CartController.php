<?php
/**
 * Controller do carrinho
 */
class CartController {
    private $cartService;
    private $serviceRepo;

    public function __construct() {
        $this->cartService = new CartService();
        $this->serviceRepo = new ServiceRepository();
    }

    public function index() {
        try {
            $cartItems = $this->cartService->getItems();
            $services = [];
            
            foreach ($cartItems as $serviceId => $quantity) {
                $service = $this->serviceRepo->findById($serviceId);
                if ($service) {
                    $services[] = [
                        'service' => $service,
                        'quantity' => $quantity
                    ];
                }
            }
            
            $total = $this->cartService->getTotal();
        } catch (Exception $e) {
            error_log("Erro no carrinho: " . $e->getMessage());
            $services = [];
            $total = 0;
        }
        
        include '../app/views/cart/index.php';
    }

    public function add() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método inválido']);
            exit;
        }
        
        try {
            $serviceId = filter_var($_POST['service_id'] ?? 0, FILTER_VALIDATE_INT);
            $quantity = filter_var($_POST['quantity'] ?? 1, FILTER_VALIDATE_INT);
            
            if (!$serviceId || $serviceId <= 0) {
                throw new Exception('ID de serviço inválido');
            }
            
            if (!$quantity || $quantity <= 0) {
                $quantity = 1;
            }
            
            $this->cartService->addItem($serviceId, $quantity);
            
            echo json_encode([
                'success' => true,
                'count' => $this->cartService->getItemCount()
            ]);
        } catch (Exception $e) {
            error_log("Erro ao adicionar ao carrinho: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $serviceId = filter_var($_POST['service_id'] ?? 0, FILTER_VALIDATE_INT);
                
                if ($serviceId && $serviceId > 0) {
                    $this->cartService->removeItem($serviceId);
                }
            } catch (Exception $e) {
                error_log("Erro ao remover do carrinho: " . $e->getMessage());
            }
            
            redirect('/cart');
        }
    }

    public function checkout() {
        if (!isLoggedIn() || isAdmin()) {
            redirect('/auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderRepo = new OrderRepository();
            $cartItems = $this->cartService->getItems();
            
            if (empty($cartItems)) {
                redirect('/cart');
            }

            $order = new Order();
            $order->restaurant_id = $_SESSION['restaurant_id'];
            $order->total_amount = $this->cartService->getTotal();
            $order->status = 'pending';
            $order->payment_status = 'pending';
            
            foreach ($cartItems as $serviceId => $quantity) {
                $service = $this->serviceRepo->findById($serviceId);
                if ($service) {
                    $order->items[] = [
                        'service_id' => $serviceId,
                        'quantity' => $quantity,
                        'price' => $service->price
                    ];
                }
            }
            
            try {
                $orderId = $orderRepo->create($order);
                if ($orderId) {
                    $this->cartService->clear();
                    redirect('/payment/select?order_id=' . $orderId);
                } else {
                    $error = 'Erro ao processar pedido';
                }
            } catch (Exception $e) {
                $error = 'Erro ao processar pedido: ' . $e->getMessage();
            }
        }
        
        include '../app/views/cart/checkout.php';
    }
}
?>