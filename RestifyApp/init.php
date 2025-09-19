<?php
/**
 * Script de inicialização do banco SQLite
 */

require_once 'config/database.php';

echo "Inicializando banco de dados SQLite...\n";

try {
    $database = new Database();
    $conn = $database->connect();
    
    if ($conn) {
        echo "✅ Banco de dados criado com sucesso!\n";
        echo "📁 Localização: " . __DIR__ . "/database/restify.db\n";
        echo "🚀 Acesse: http://localhost:8000\n";
        echo "👤 Admin: admin@restify.com / admin123\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>