<?php
/**
 * Repository para operações com serviços
 */
class ServiceRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Listar todos os serviços
     */
    public function findAll() {
        $query = "SELECT * FROM services ORDER BY type, price";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $services = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[] = new Service($row);
        }
        
        return $services;
    }

    /**
     * Buscar serviço por ID
     */
    public function findById($id) {
        $query = "SELECT * FROM services WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Service($data) : null;
    }

    /**
     * Buscar serviços por tipo
     */
    public function findByType($type) {
        $query = "SELECT * FROM services WHERE type = :type ORDER BY price";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        
        $services = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[] = new Service($row);
        }
        
        return $services;
    }

    /**
     * Criar novo serviço
     */
    public function create($service) {
        $query = "INSERT INTO services (name, description, price, type) VALUES (:name, :description, :price, :type)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $service->name);
        $stmt->bindParam(':description', $service->description);
        $stmt->bindParam(':price', $service->price);
        $stmt->bindParam(':type', $service->type);
        
        return $stmt->execute();
    }

    /**
     * Atualizar serviço
     */
    public function update($service) {
        $query = "UPDATE services SET name = :name, description = :description, price = :price, type = :type WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $service->name);
        $stmt->bindParam(':description', $service->description);
        $stmt->bindParam(':price', $service->price);
        $stmt->bindParam(':type', $service->type);
        $stmt->bindParam(':id', $service->id);
        
        return $stmt->execute();
    }

    /**
     * Excluir serviço
     */
    public function delete($id) {
        $query = "DELETE FROM services WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>