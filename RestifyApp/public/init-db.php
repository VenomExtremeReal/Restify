<?php
require_once '../config/config.php';

try {
    $database = new Database();
    $conn = $database->connect();
    
    if ($conn) {
        echo "✅ Banco de dados conectado com sucesso!<br>";
        
        // Testar se há serviços
        $stmt = $conn->query("SELECT COUNT(*) as count FROM services");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Serviços cadastrados: " . $result['count'] . "<br>";
        
        echo "🚀 <a href='index.php'>Acessar aplicação</a><br>";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}
?>