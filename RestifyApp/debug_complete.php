<?php
/**
 * Debug completo do sistema Restify
 * Execute: php debug_complete.php ou acesse via web
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$isWeb = isset($_SERVER['HTTP_HOST']);
if ($isWeb) echo "<!DOCTYPE html><html><head><title>Debug Restify</title></head><body>";

echo $isWeb ? "<h2>🔍 Debug Completo - Sistema Restify</h2>" : "🔍 Debug Completo - Sistema Restify\n";

try {
    // Configurações
    require_once 'config/config.php';
    echo $isWeb ? "✅ Config carregado<br>" : "✅ Config carregado\n";
    
    // Database Singleton
    $database = Database::getInstance();
    $conn = $database->connect();
    echo $isWeb ? "✅ Database Singleton OK<br>" : "✅ Database Singleton OK\n";
    
    // Services
    require_once 'app/services/EfiPaymentService.php';
    $efiService = new EfiPaymentService('sandbox');
    echo $isWeb ? "✅ EfiPaymentService OK<br>" : "✅ EfiPaymentService OK\n";
    
    require_once 'app/services/PaymentServiceFactory.php';
    $paymentService = PaymentServiceFactory::create('mock');
    echo $isWeb ? "✅ Factory Pattern OK<br>" : "✅ Factory Pattern OK\n";
    
    require_once 'app/services/NotificationService.php';
    $notificationService = new NotificationService();
    $notificationService->attach(new EmailNotificationObserver());
    echo $isWeb ? "✅ Observer Pattern OK<br>" : "✅ Observer Pattern OK\n";
    
    $exportService = new ExportService();
    $exportService->setStrategy(new JsonExportStrategy());
    echo $isWeb ? "✅ Strategy Pattern OK<br>" : "✅ Strategy Pattern OK\n";
    
    // Controllers
    require_once 'app/controllers/AuthController.php';
    new AuthController();
    echo $isWeb ? "✅ AuthController OK<br>" : "✅ AuthController OK\n";
    
    require_once 'app/controllers/HomeController.php';
    new HomeController();
    echo $isWeb ? "✅ HomeController OK<br>" : "✅ HomeController OK\n";
    
    require_once 'app/controllers/PaymentController.php';
    new PaymentController();
    echo $isWeb ? "✅ PaymentController OK<br>" : "✅ PaymentController OK\n";
    
    // Repositories
    require_once 'app/repositories/RestaurantRepository.php';
    new RestaurantRepository();
    echo $isWeb ? "✅ RestaurantRepository OK<br>" : "✅ RestaurantRepository OK\n";
    
    require_once 'app/repositories/OrderRepository.php';
    new OrderRepository();
    echo $isWeb ? "✅ OrderRepository OK<br>" : "✅ OrderRepository OK\n";
    
    // Database info
    $stmt = $conn->query("SELECT COUNT(*) as count FROM services");
    $services = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT COUNT(*) as count FROM restaurants");
    $restaurants = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo $isWeb ? "<br><h3>📊 Sistema Status:</h3>" : "\n📊 Sistema Status:\n";
    echo $isWeb ? "Serviços: {$services['count']}<br>" : "Serviços: {$services['count']}\n";
    echo $isWeb ? "Restaurantes: {$restaurants['count']}<br>" : "Restaurantes: {$restaurants['count']}\n";
    echo $isWeb ? "<br><strong>✅ SISTEMA FUNCIONANDO PERFEITAMENTE!</strong><br>" : "\n✅ SISTEMA FUNCIONANDO PERFEITAMENTE!\n";
    
    if ($isWeb) echo "<p><a href='public/'>🚀 Acessar Sistema</a></p>";
    
} catch (Exception $e) {
    echo $isWeb ? "❌ Erro: " . $e->getMessage() . "<br>" : "❌ Erro: " . $e->getMessage() . "\n";
    echo $isWeb ? "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>" : "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")\n";
} catch (Error $e) {
    echo $isWeb ? "❌ Fatal: " . $e->getMessage() . "<br>" : "❌ Fatal: " . $e->getMessage() . "\n";
    echo $isWeb ? "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>" : "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")\n";
}

if ($isWeb) echo "</body></html>";
?>