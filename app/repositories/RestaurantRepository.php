<?php
/**
 * Repository para operações com restaurantes
 */
class RestaurantRepository {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->connect();
    }

    /**
     * Criar novo restaurante
     */
    public function create($restaurant) {
        try {
            $query = "INSERT INTO restaurants (name, email, whatsapp, address, password) 
                      VALUES (:name, :email, :whatsapp, :address, :password)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $restaurant->name);
            $stmt->bindParam(':email', $restaurant->email);
            $stmt->bindParam(':whatsapp', $restaurant->whatsapp);
            $stmt->bindParam(':address', $restaurant->address);
            $stmt->bindParam(':password', $restaurant->password);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao criar restaurante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar restaurante por email
     */
    public function findByEmail($email) {
        try {
            $query = "SELECT * FROM restaurants WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new Restaurant($data) : null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar restaurante por email: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar restaurante por ID
     */
    public function findById($id) {
        try {
            $query = "SELECT * FROM restaurants WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new Restaurant($data) : null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar restaurante por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar todos os restaurantes
     */
    public function findAll() {
        try {
            $query = "SELECT * FROM restaurants WHERE id != 999 ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $restaurants = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $restaurants[] = new Restaurant($row);
            }
            
            return $restaurants;
        } catch (PDOException $e) {
            error_log("Erro ao listar restaurantes: " . $e->getMessage());
            return [];
        }
    }
}
?>