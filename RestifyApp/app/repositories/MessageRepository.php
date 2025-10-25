<?php
/**
 * Repository para operações com mensagens
 */
class MessageRepository {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->connect();
    }

    /**
     * Criar nova mensagem
     */
    public function create($message) {
        $query = "INSERT INTO messages (restaurant_id, sender_type, message) 
                  VALUES (:restaurant_id, :sender_type, :message)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':restaurant_id', $message->restaurant_id);
        $stmt->bindParam(':sender_type', $message->sender_type);
        $stmt->bindParam(':message', $message->message);
        
        return $stmt->execute();
    }

    /**
     * Buscar mensagens por restaurante
     */
    public function findByRestaurant($restaurantId) {
        if (!$restaurantId) {
            return [];
        }
        
        $query = "SELECT * FROM messages WHERE restaurant_id = :restaurant_id ORDER BY created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
        
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message($row);
        }
        
        return $messages;
    }

    /**
     * Buscar últimas mensagens de cada restaurante (admin)
     */
    public function findLatestByRestaurant() {
        $query = "SELECT m.*, r.name as restaurant_name 
                  FROM messages m 
                  JOIN restaurants r ON m.restaurant_id = r.id 
                  WHERE m.id IN (
                      SELECT MAX(id) FROM messages GROUP BY restaurant_id
                  ) 
                  ORDER BY m.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>