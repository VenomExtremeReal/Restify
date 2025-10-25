<?php
/**
 * Service para autenticação
 */
class AuthService {
    private $restaurantRepo;

    public function __construct() {
        $this->restaurantRepo = new RestaurantRepository();
    }

    /**
     * Login do restaurante
     */
    public function loginRestaurant($email, $password) {
        $restaurant = $this->restaurantRepo->findByEmail($email);
        
        if ($restaurant && $restaurant->password === $password) {
            $_SESSION['restaurant_id'] = $restaurant->id;
            $_SESSION['restaurant_name'] = $restaurant->name;
            return true;
        }
        
        return false;
    }

    /**
     * Login do admin
     */
    public function loginAdmin($email, $password) {
        // Credenciais fixas do admin
        if ($email === 'admin@restify.com' && $password === 'admin123') {
            $_SESSION['admin'] = true;
            $_SESSION['admin_name'] = 'Administrador';
            return true;
        }
        
        return false;
    }

    /**
     * Logout
     */
    public function logout() {
        session_destroy();
    }

    /**
     * Registrar novo restaurante
     */
    public function register($data) {
        // Verificar se email já existe
        if ($this->restaurantRepo->findByEmail($data['email'])) {
            return ['success' => false, 'message' => 'Email já cadastrado'];
        }

        $restaurant = new Restaurant($data);
        
        if ($this->restaurantRepo->create($restaurant)) {
            return ['success' => true, 'message' => 'Cadastro realizado com sucesso'];
        }
        
        return ['success' => false, 'message' => 'Erro ao cadastrar'];
    }
}
?>