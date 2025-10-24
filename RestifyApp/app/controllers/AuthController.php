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
            // Validar CSRF (em produção)
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            
            if (empty($email)) {
                $error = t('email') . ' é obrigatório';
            } else {
                $result = $this->passwordResetService->requestReset($email);
                
                if ($result['success']) {
                    $success = $result['message'];
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        include '../app/views/auth/forgot-password.php';
    }
    
    public function resetPassword() {
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validações
            if (empty($newPassword) || empty($confirmPassword)) {
                $error = 'Todos os campos são obrigatórios';
            } elseif ($newPassword !== $confirmPassword) {
                $error = t('passwords_dont_match');
            } elseif (strlen($newPassword) < 6) {
                $error = 'Senha deve ter pelo menos 6 caracteres';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $newPassword)) {
                $error = 'Senha deve conter ao menos: 1 minúscula, 1 maiúscula e 1 número';
            } else {
                $result = $this->passwordResetService->resetPassword($token, $newPassword);
                
                if ($result['success']) {
                    // Limpar sessão por segurança
                    session_regenerate_id(true);
                    redirect('/auth/login?reset=1');
                } else {
                    $error = $result['message'];
                }
            }
        }
        
        // Validar token antes de mostrar formulário
        if (empty($token) || !$this->passwordResetService->validateToken($token)) {
            $error = t('invalid_token');
            $tokenValid = false;
        } else {
            $tokenValid = true;
        }
        
        include '../app/views/auth/reset-password.php';
    }
}
?>