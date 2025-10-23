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
        
        // Executar migrações adicionais
        $migrations = file_get_contents(__DIR__ . '/database/password_reset.sql');
        $conn->exec($migrations);
        echo "✅ Migrações aplicadas com sucesso!\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    // Tentar aplicar migrações mesmo com erro
    try {
        $database = new Database();
        $conn = $database->connect();
        $migrations = file_get_contents(__DIR__ . '/database/password_reset.sql');
        $conn->exec($migrations);
        echo "✅ Migrações aplicadas!\n";
    } catch (Exception $e2) {
        echo "❌ Erro nas migrações: " . $e2->getMessage() . "\n";
    }
}
?>