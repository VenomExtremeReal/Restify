-- Tabela para tokens de recuperação de senha
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL,
    token TEXT NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Índices para performance e segurança
CREATE INDEX IF NOT EXISTS idx_password_reset_email ON password_reset_tokens(email);
CREATE INDEX IF NOT EXISTS idx_password_reset_token ON password_reset_tokens(token);
CREATE INDEX IF NOT EXISTS idx_password_reset_expires ON password_reset_tokens(expires_at);
CREATE INDEX IF NOT EXISTS idx_password_reset_created ON password_reset_tokens(created_at);

-- Adicionar colunas para preferências do usuário (apenas se não existirem)
PRAGMA table_info(restaurants);
-- Trigger para limpeza automática (SQLite)
CREATE TRIGGER IF NOT EXISTS cleanup_expired_tokens
AFTER INSERT ON password_reset_tokens
BEGIN
    DELETE FROM password_reset_tokens 
    WHERE expires_at < datetime('now') AND used = 1;
END;