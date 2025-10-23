<?php
/**
 * Repository para operações com restaurantes
 */
class RestaurantRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Criar novo restaurante
     */
    public function create($restaurant) {
        $query = "INSERT INTO restaurants (name, email, whatsapp, address, password) 
                  VALUES (:name, :email, :whatsapp, :address, :password)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $restaurant->name);
        $stmt->bindParam(':email', $restaurant->email);
        $stmt->bindParam(':whatsapp', $restaurant->whatsapp);
        $stmt->bindParam(':address', $restaurant->address);
        $stmt->bindParam(':password', $restaurant->password);
        
        return $stmt->execute();
    }

    /**
     * Buscar restaurante por email
     */
    public function findByEmail($email) {
        $query = "SELECT * FROM restaurants WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Restaurant($data) : null;
    }

    /**
     * Buscar restaurante por ID
     */
    public function findById($id) {
        $query = "SELECT * FROM restaurants WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Restaurant($data) : null;
    }

    /**
     * Listar todos os restaurantes
     */
    public function findAll() {
        $query = "SELECT * FROM restaurants ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $restaurants = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $restaurants[] = new Restaurant($row);
        }
        
        return $restaurants;
    }
}
?>