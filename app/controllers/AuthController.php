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
            try {
                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';
                $type = $_POST['type'] ?? 'restaurant';
                
                if (empty($email) || empty($password)) {
                    throw new Exception('Email e senha são obrigatórios');
                }
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Email inválido');
                }
                
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
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        include '../app/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8'),
                    'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
                    'whatsapp' => htmlspecialchars(trim($_POST['whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8'),
                    'address' => htmlspecialchars(trim($_POST['address'] ?? ''), ENT_QUOTES, 'UTF-8'),
                    'password' => $_POST['password'] ?? ''
                ];
                
                if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                    throw new Exception('Todos os campos são obrigatórios');
                }
                
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Email inválido');
                }
                
                $result = $this->authService->register($data);
                
                if ($result['success']) {
                    redirect('/auth/login?success=1');
                } else {
                    $error = $result['message'];
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
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