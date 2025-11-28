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
            $query = "INSERT INTO orders (restaurant_id, total_amount, status, payment_method, payment_status) 
                      VALUES (:restaurant_id, :total_amount, :status, :payment_method, :payment_status)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':restaurant_id', $order->restaurant_id);
            $stmt->bindParam(':total_amount', $order->total_amount);
            $stmt->bindParam(':status', $order->status);
            $stmt->bindParam(':payment_method', $order->payment_method);
            $stmt->bindParam(':payment_status', $order->payment_status);
            
            $stmt->execute();
            $orderId = $this->db->lastInsertId();
            
            // Inserir itens do pedido
            if (!empty($order->items)) {
                $itemQuery = "INSERT INTO order_items (order_id, service_id, quantity, price) 
                              VALUES (:order_id, :service_id, :quantity, :price)";
                $itemStmt = $this->db->prepare($itemQuery);
                
                foreach ($order->items as $item) {
                    $itemStmt->bindParam(':order_id', $orderId);
                    $itemStmt->bindParam(':service_id', $item['service_id']);
                    $itemStmt->bindParam(':quantity', $item['quantity']);
                    $itemStmt->bindParam(':price', $item['price']);
                    $itemStmt->execute();
                }
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Buscar pedido por ID
     */
    public function findById($id) {
        try {
            $query = "SELECT o.*, r.name as restaurant_name, r.email as restaurant_email 
                      FROM orders o 
                      LEFT JOIN restaurants r ON o.restaurant_id = r.id 
                      WHERE o.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) return null;
            
            $order = new Order($data);
            $order->items = $this->getOrderItems($id);
            
            return $order;
        } catch (PDOException $e) {
            error_log("Erro ao buscar pedido: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar pedidos por restaurante
     */
    public function findByRestaurant($restaurantId) {
        $query = "SELECT * FROM orders WHERE restaurant_id = :restaurant_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':restaurant_id', $restaurantId);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order($row);
            $order->items = $this->getOrderItems($order->id);
            $orders[] = $order;
        }
        
        return $orders;
    }

    /**
     * Listar todos os pedidos
     */
    public function findAll() {
        $query = "SELECT o.*, r.name as restaurant_name, r.email as restaurant_email 
                  FROM orders o 
                  LEFT JOIN restaurants r ON o.restaurant_id = r.id 
                  ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order($row);
            $order->restaurant_name = $row['restaurant_name'];
            $order->restaurant_email = $row['restaurant_email'];
            $order->items = $this->getOrderItems($order->id);
            $orders[] = $order;
        }
        
        return $orders;
    }

    /**
     * Atualizar status do pedido
     */
    public function updateStatus($id, $status) {
        try {
            $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                throw new Exception('Status inválido');
            }
            
            $query = "UPDATE orders SET status = :status, updated_at = datetime('now', 'localtime') WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao atualizar status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar informações de pagamento
     */
    public function updatePayment($id, $paymentId, $paymentStatus, $paymentMethod = null) {
        $query = "UPDATE orders SET payment_id = :payment_id, payment_status = :payment_status";
        
        if ($paymentMethod) {
            $query .= ", payment_method = :payment_method";
        }
        
        $query .= ", updated_at = datetime('now', 'localtime') WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':payment_id', $paymentId);
        $stmt->bindParam(':payment_status', $paymentStatus);
        $stmt->bindParam(':id', $id);
        
        if ($paymentMethod) {
            $stmt->bindParam(':payment_method', $paymentMethod);
        }
        
        return $stmt->execute();
    }

    /**
     * Obter itens do pedido
     */
    private function getOrderItems($orderId) {
        $query = "SELECT oi.*, s.name as service_name, s.description as service_description 
                  FROM order_items oi 
                  LEFT JOIN services s ON oi.service_id = s.id 
                  WHERE oi.order_id = :order_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }
        
        return $items;
    }
}
?>