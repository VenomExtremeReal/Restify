<?php
echo "<h2>Debug do Sistema Restify</h2>";

echo "<h3>1. Informações do Servidor</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";

echo "<h3>2. Testando Configuração</h3>";
try {
    require_once '../config/config.php';
    echo "✅ Config carregado<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
} catch (Exception $e) {
    echo "❌ Erro no config: " . $e->getMessage() . "<br>";
}

echo "<h3>3. Testando Banco de Dados</h3>";
try {
    $database = new Database();
    $conn = $database->connect();
    echo "✅ Banco conectado<br>";
    
    $stmt = $conn->query("SELECT COUNT(*) as count FROM services");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Serviços: " . $result['count'] . "<br>";
} catch (Exception $e) {
    echo "❌ Erro no banco: " . $e->getMessage() . "<br>";
}

echo "<h3>4. Testando Controllers</h3>";
try {
    $controller = new HomeController();
    echo "✅ HomeController carregado<br>";
} catch (Exception $e) {
    echo "❌ Erro no HomeController: " . $e->getMessage() . "<br>";
}

echo "<h3>5. Links de Teste</h3>";
echo "<a href='index.php'>Página Inicial</a><br>";
echo "<a href='index.php?debug=1'>Página Inicial com Debug</a><br>";
echo "<a href='auth/login'>Login</a><br>";
?>