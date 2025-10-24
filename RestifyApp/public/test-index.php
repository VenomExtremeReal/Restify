<?php
/**
 * Teste básico do sistema
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Testando sistema...<br>";

try {
    require_once '../config/config.php';
    echo "✅ Config OK<br>";
    
    echo "BASE_URL: " . BASE_URL . "<br>";
    
    $controller = new HomeController();
    echo "✅ HomeController OK<br>";
    
    echo "Sistema funcionando!";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString();
}
?>