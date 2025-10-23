<?php
/**
 * Controller de autenticação
 */
class AuthController {
    private $authService;
    private $passwordResetService;

    public function __construct() {
        $this->authService = new AuthService();
        $this->passwordResetService = new PasswordResetService();
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
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $result = $this->passwordResetService->requestReset($email);
            
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
        
        include '../app/views/auth/forgot-password.php';
    }
    
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if ($newPassword !== $confirmPassword) {
                $error = 'Senhas não coincidem';
            } else {
                $result = $this->passwordResetService->resetPassword($token, $newPassword);
                
                if ($result['success']) {
                    redirect('/auth/login?reset=1');
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        if (!$this->passwordResetService->validateToken($token)) {
            $error = 'Token inválido ou expirado';
        }
        
        include '../app/views/auth/reset-password.php';
    }
}
?>