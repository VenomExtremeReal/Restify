<?php
/**
 * Controller do painel do restaurante
 */
class RestaurantController {
    private $orderRepo;
    private $messageRepo;

    public function __construct() {
        if (!isLoggedIn() || isAdmin()) {
            redirect('/auth/login');
        }
        
        $this->orderRepo = new OrderRepository();
        $this->messageRepo = new MessageRepository();
    }

    public function dashboard() {
        $orders = $this->orderRepo->findByRestaurant($_SESSION['restaurant_id']);
        include '../app/views/restaurant/dashboard.php';
    }

    public function orders() {
        $orders = $this->orderRepo->findByRestaurant($_SESSION['restaurant_id']);
        include '../app/views/restaurant/orders.php';
    }

    public function chat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageText = $_POST['message'] ?? '';
            
            if (!empty($messageText)) {
                $message = new Message();
                $message->restaurant_id = $_SESSION['restaurant_id'];
                $message->sender_type = 'restaurant';
                $message->message = $messageText;
                
                $this->messageRepo->create($message);
                
                // Redirecionar para evitar reenvio
                redirect('/restaurant/chat');
            }
        }
        
        $messages = $this->messageRepo->findByRestaurant($_SESSION['restaurant_id']);
        include '../app/views/restaurant/chat.php';
    }

    public function getMessages() {
        $restaurantId = $_SESSION['restaurant_id'] ?? null;
        
        if (!$restaurantId) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }
        
        $messages = $this->messageRepo->findByRestaurant($restaurantId);
        
        header('Content-Type: application/json');
        echo json_encode($messages);
        exit;
    }
}
?>