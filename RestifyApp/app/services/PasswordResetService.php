<?php
class PasswordResetService {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    public function requestReset($email) {
        $stmt = $this->db->prepare("SELECT id FROM restaurants WHERE email = ?");
        $stmt->execute([$email]);
        
        if (!$stmt->fetch()) {
            return ['success' => false, 'message' => 'E-mail não encontrado'];
        }
        
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->db->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);
        
        // Simular envio de email (em produção, usar PHPMailer ou similar)
        $resetLink = "http://localhost/RestifyApp/public/auth/reset-password?token=" . $token;
        error_log("Reset link for $email: $resetLink");
        
        return ['success' => true, 'message' => 'Link de recuperação enviado para seu e-mail'];
    }
    
    public function validateToken($token) {
        $stmt = $this->db->prepare("SELECT email FROM password_reset_tokens WHERE token = ? AND expires_at > datetime('now') AND used = 0");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }
    
    public function resetPassword($token, $newPassword) {
        $tokenData = $this->validateToken($token);
        if (!$tokenData) {
            return ['success' => false, 'message' => 'Token inválido ou expirado'];
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("UPDATE restaurants SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $tokenData['email']]);
        
        $stmt = $this->db->prepare("UPDATE password_reset_tokens SET used = 1 WHERE token = ?");
        $stmt->execute([$token]);
        
        return ['success' => true, 'message' => 'Senha redefinida com sucesso'];
    }
}
?>