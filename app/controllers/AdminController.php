<?php
/**
 * Controller do painel administrativo
 */
class AdminController {
    private $orderRepo;
    private $restaurantRepo;
    private $messageRepo;
    private $serviceRepo;

    public function __construct() {
        if (!isAdmin()) {
            redirect('/auth/login');
        }
        
        $this->orderRepo = new OrderRepository();
        $this->restaurantRepo = new RestaurantRepository();
        $this->messageRepo = new MessageRepository();
        $this->serviceRepo = new ServiceRepository();
    }

    public function dashboard() {
        $orders = $this->orderRepo->findAll();
        $restaurants = $this->restaurantRepo->findAll();
        
        include '../app/views/admin/dashboard.php';
    }

    public function orders() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $orderId = $_POST['order_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            $this->orderRepo->updateStatus($orderId, $status);
        }
        
        $orders = $this->orderRepo->findAll();
        include '../app/views/admin/orders.php';
    }

    public function restaurants() {
        $restaurants = $this->restaurantRepo->findAll();
        include '../app/views/admin/restaurants.php';
    }

    public function chat($restaurantId = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageText = $_POST['message'] ?? '';
            $postRestaurantId = $_POST['restaurant_id'] ?? 0;
            
            if (!empty($messageText) && $postRestaurantId) {
                $message = new Message();
                $message->restaurant_id = $postRestaurantId;
                $message->sender_type = 'admin';
                $message->message = $messageText;
                
                $this->messageRepo->create($message);
                
                // Redirecionar para evitar reenvio
                redirect('/admin/chat/' . $postRestaurantId);
            }
        }
        
        $conversations = $this->messageRepo->findLatestByRestaurant();
        $messages = [];
        $selectedRestaurant = null;
        
        if ($restaurantId) {
            $messages = $this->messageRepo->findByRestaurant($restaurantId);
            $selectedRestaurant = $this->restaurantRepo->findById($restaurantId);
        }
        
        include '../app/views/admin/chat.php';
    }

    public function getMessages($restaurantId) {
        if (!$restaurantId || !is_numeric($restaurantId)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }
        
        $messages = $this->messageRepo->findByRestaurant($restaurantId);
        
        header('Content-Type: application/json');
        echo json_encode($messages);
        exit;
    }

    public function services() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_service'])) {
                $this->serviceRepo->delete($_POST['service_id']);
            } elseif (isset($_POST['create_service'])) {
                $service = new Service($_POST);
                $this->serviceRepo->create($service);
            } elseif (isset($_POST['update_service'])) {
                $service = new Service($_POST);
                $this->serviceRepo->update($service);
            }
        }
        
        $services = $this->serviceRepo->findAll();
        include '../app/views/admin/services.php';
    }

    public function editService($id) {
        $service = $this->serviceRepo->findById($id);
        if (!$service) {
            redirect('/admin/services');
        }
        
        include '../app/views/admin/edit-service.php';
    }
}
?>