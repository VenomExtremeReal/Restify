<?php
/**
 * Repository para operações com pedidos
 */
class OrderRepository {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->connect();
    }

    /**
     * Criar novo pedido
     */
    public function create($order) {
        try {
            $this->db->beginTransaction();
            
            // Inserir pedido
            $query = "INSERT INTO orders (restaurant_id, total_amount, status) 
                      VALUES (:restaurant_id, :total_amount, :status)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':restaurant_id', $order->restaurant_id);
            $stmt->bindParam(':total_amount', $order->total_amount);
            $stmt->bindParam(':status', $order->status);
            $stmt->execute();
            
            $orderId = $this->db->lastInsertId();
            
            // Inserir itens do pedido
            foreach ($order->items as $item) {
                $itemQuery = "INSERT INTO order_items (order_id, service_id, quantity, price) 
                              VALUES (:order_id, :service_id, :quantity, :price)";
                $itemStmt = $this->db->prepare($itemQuery);
                $itemStmt->bindParam(':order_id', $orderId);
                $itemStmt->bindParam(':service_id', $item['service_id']);
                $itemStmt->bindParam(':quantity', $item['quantity']);
                $itemStmt->bindParam(':price', $item['price']);
                $itemStmt->execute();
            }
            
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * Listar pedidos por restaurante
     */
    public function findByRestaurant($restaurantId) {
        $query = "SELECT * FROM orders WHERE restaurant_id = :restaurant_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':restaurant_id', $restaurantId);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = new Order($row);
        }
        
        return $orders;
    }

    /**
     * Listar todos os pedidos (admin)
     */
    public function findAll() {
        $query = "SELECT o.*, r.name as restaurant_name 
                  FROM orders o 
                  JOIN restaurants r ON o.restaurant_id = r.id 
                  ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Atualizar status do pedido
     */
    public function updateStatus($orderId, $status) {
        $query = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $orderId);
        
        return $stmt->execute();
    }
}
?>