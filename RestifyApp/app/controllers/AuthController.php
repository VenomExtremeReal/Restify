<?php
/**
 * Controller de autenticação
 */
class AuthController {
    private $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $type = $_POST['type'] ?? 'restaurant';
            
            if ($type === 'admin') {
                if ($this->authService->loginAdmin($email, $password)) {
                    redirect('/admin/dashboard');
                } else {
                    $error = 'Credenciais inválidas';
                }
            } else {
                if ($this->authService->loginRestaurant($email, $password)) {
                    redirect('/restaurant/dashboard');
                } else {
                    $error = 'Credenciais inválidas';
                }
            }
        }
        
        include '../app/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->register($_POST);
            
            if ($result['success']) {
                redirect('/auth/login?success=1');
            } else {
                $error = $result['message'];
            }
        }
        
        include '../app/views/auth/register.php';
    }

    public function logout() {
        $this->authService->logout();
        redirect('/');
    }
}
?>