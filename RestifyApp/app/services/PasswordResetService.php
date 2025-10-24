<?php
// PHPMailer será carregado via autoload do config.php

class PasswordResetService {
    private $db;
    private const TOKEN_EXPIRY_HOURS = 1;
    private const MAX_RESET_ATTEMPTS = 3;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    public function requestReset($email) {
        try {
            // Validar formato do email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => t('invalid_format')];
            }
            
            // Verificar rate limiting
            if (!$this->checkRateLimit($email)) {
                return ['success' => false, 'message' => 'Muitas tentativas. Tente novamente em 15 minutos.'];
            }
            
            // Verificar se email existe (sem revelar se existe ou não)
            $stmt = $this->db->prepare("SELECT id FROM restaurants WHERE email = ?");
            $stmt->execute([$email]);
            $userExists = $stmt->fetch();
            
            // Sempre retornar sucesso para evitar enumeração de emails
            if ($userExists) {
                $this->generateAndSendToken($email);
            }
            
            return ['success' => true, 'message' => t('reset_link_sent')];
            
        } catch (Exception $e) {
            error_log('Password reset error: ' . $e->getMessage());
            return ['success' => false, 'message' => t('server_error')];
        }
    }
    
    private function checkRateLimit($email) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as attempts 
            FROM password_reset_tokens 
            WHERE email = ? AND created_at > datetime('now', '-15 minutes')
        ");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        
        return $result['attempts'] < self::MAX_RESET_ATTEMPTS;
    }
    
    private function generateAndSendToken($email) {
        // Invalidar tokens anteriores
        $stmt = $this->db->prepare("UPDATE password_reset_tokens SET used = 1 WHERE email = ? AND used = 0");
        $stmt->execute([$email]);
        
        // Gerar token seguro
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_EXPIRY_HOURS . ' hours'));
        
        // Armazenar token hasheado
        $stmt = $this->db->prepare("
            INSERT INTO password_reset_tokens (email, token, expires_at) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$email, $hashedToken, $expires]);
        
        // Enviar email
        $this->sendResetEmail($email, $token);
    }
    
    private function sendResetEmail($email, $token) {
        $resetLink = BASE_URL . '/auth/reset-password?token=' . urlencode($token);
        
        try {
            $this->sendWithPHPMailer($email, $resetLink);
            error_log("Password reset email sent successfully to: $email");
        } catch (Exception $e) {
            error_log("Failed to send password reset email to $email: " . $e->getMessage());
            // Em desenvolvimento, mostrar o link no log
            error_log("Password reset link for $email: $resetLink");
            throw $e;
        }
    }
    
    private function sendWithPHPMailer($email, $resetLink) {
        if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            throw new Exception('PHPMailer não está instalado');
        }
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'restifyapp.dpo@gmail.com';
        $mail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('restifyapp.dpo@gmail.com', 'Restify');
        $mail->addAddress($email);
        $mail->addReplyTo('restifyapp.dpo@gmail.com', 'Restify');
        
        $mail->isHTML(false);
        $mail->Subject = 'Recuperação de Senha - Restify';
        $mail->Body = "Clique no link para redefinir sua senha: $resetLink";
        
        $mail->send();
    }
    

    

    
    public function validateToken($token) {
        if (empty($token)) {
            return false;
        }
        
        $hashedToken = hash('sha256', $token);
        $stmt = $this->db->prepare("
            SELECT email FROM password_reset_tokens 
            WHERE token = ? AND expires_at > datetime('now') AND used = 0
        ");
        $stmt->execute([$hashedToken]);
        return $stmt->fetch();
    }
    
    public function resetPassword($token, $newPassword) {
        try {
            // Validar token
            $tokenData = $this->validateToken($token);
            if (!$tokenData) {
                return ['success' => false, 'message' => t('invalid_token')];
            }
            
            // Validar senha
            if (strlen($newPassword) < 6) {
                return ['success' => false, 'message' => 'Senha deve ter pelo menos 6 caracteres'];
            }
            
            // Hash da nova senha
            $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536,
                'time_cost' => 4,
                'threads' => 3
            ]);
            
            // Iniciar transação
            $this->db->beginTransaction();
            
            // Atualizar senha
            $stmt = $this->db->prepare("UPDATE restaurants SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $tokenData['email']]);
            
            // Invalidar token
            $hashedToken = hash('sha256', $token);
            $stmt = $this->db->prepare("UPDATE password_reset_tokens SET used = 1 WHERE token = ?");
            $stmt->execute([$hashedToken]);
            
            $this->db->commit();
            
            return ['success' => true, 'message' => t('password_reset_complete')];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Password reset error: ' . $e->getMessage());
            return ['success' => false, 'message' => t('server_error')];
        }
    }
    
    public function cleanupExpiredTokens() {
        $stmt = $this->db->prepare("DELETE FROM password_reset_tokens WHERE expires_at < datetime('now')");
        $stmt->execute();
    }
}
?>