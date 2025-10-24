<?php
/**
 * Script de debug para Apache
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug do Sistema Restify</h1>";

echo "<h2>1. Informações do PHP</h2>";
echo "Versão: " . PHP_VERSION . "<br>";
echo "Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";

echo "<h2>2. Caminhos</h2>";
echo "DIR atual: " . __DIR__ . "<br>";
echo "Diretório pai: " . dirname(__DIR__) . "<br>";

echo "<h2>3. Arquivos importantes</h2>";
$files = [
    '../config/config.php',
    '../config/autoload.php',
    '../vendor/autoload.php',
    '../.env'
];

foreach ($files as $file) {
    $fullPath = __DIR__ . '/' . $file;
    echo "$file: " . (file_exists($fullPath) ? "✅ Existe" : "❌ Não existe") . "<br>";
}

echo "<h2>4. Testando autoload</h2>";
try {
    require_once '../config/config.php';
    echo "✅ Config carregado<br>";
} catch (Exception $e) {
    echo "❌ Erro no config: " . $e->getMessage() . "<br>";
}

echo "<h2>5. Testando classes</h2>";
$classes = ['Database', 'AuthService', 'PasswordResetService'];
foreach ($classes as $class) {
    echo "$class: " . (class_exists($class) ? "✅ Carregada" : "❌ Não encontrada") . "<br>";
}

echo "<h2>6. PHPMailer</h2>";
echo "PHPMailer: " . (class_exists('PHPMailer\\PHPMailer\\PHPMailer') ? "✅ Disponível" : "❌ Não disponível") . "<br>";

echo "<h2>7. Variáveis de ambiente</h2>";
echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'Não definida') . "<br>";
echo "SMTP_HOST: " . ($_ENV['SMTP_HOST'] ?? 'Não definida') . "<br>";

phpinfo();
?>