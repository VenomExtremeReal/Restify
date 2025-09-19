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
        
        include '../app/views/cart/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $serviceId = $_POST['service_id'] ?? 0;
            $quantity = $_POST['quantity'] ?? 1;
            
            $this->cartService->addItem($serviceId, $quantity);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'count' => $this->cartService->getItemCount()
            ]);
            exit;
        }
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $serviceId = $_POST['service_id'] ?? 0;
            $this->cartService->removeItem($serviceId);
            
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
            
            if ($orderRepo->create($order)) {
                $this->cartService->clear();
                redirect('/restaurant/orders?success=1');
            } else {
                $error = 'Erro ao processar pedido';
            }
        }
        
        include '../app/views/cart/checkout.php';
    }
}
?>