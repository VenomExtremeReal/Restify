<?php
/**
 * Arquivo principal da aplicação Restify
 * Sistema de roteamento simples
 */

require_once '../config/config.php';
require_once '../config/i18n.php';

// Inicializar idioma
if (!isset($_SESSION['language']) && !isset($_COOKIE['language'])) {
    $_SESSION['language'] = 'pt';
    setcookie('language', 'pt', time() + (86400 * 30), '/');
}
$language = $_SESSION['language'] ?? $_COOKIE['language'] ?? 'pt';
I18n::init($language);

// Sistema de roteamento completamente universal
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Detectar caminho base automaticamente
$scriptDir = dirname($scriptName);
$basePath = str_replace('\\', '/', $scriptDir);

// Normalizar base path
if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}

// Remover base path da URI
if ($basePath && strpos($requestUri, $basePath) === 0) {
    $path = substr($requestUri, strlen($basePath));
} else {
    $path = $requestUri;
}

// Limpar path completamente
$path = parse_url($path, PHP_URL_PATH); // Remove query string e fragments
$path = trim($path, '/'); // Remove barras iniciais e finais

// Remover index.php se presente no path
if (strpos($path, 'index.php') === 0) {
    $path = substr($path, 9); // Remove 'index.php'
    $path = ltrim($path, '/');
}

// Debug para desenvolvimento
if (isset($_GET['debug_route'])) {
    echo "Request URI: $requestUri<br>";
    echo "Script Name: $scriptName<br>";
    echo "Script Dir: $scriptDir<br>";
    echo "Base Path: '$basePath'<br>";
    echo "Final Path: '$path'<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
    exit;
}

// Definir rotas
$routes = [
    '' => ['HomeController', 'index'],
    'home' => ['HomeController', 'index'],
    'index.php' => ['HomeController', 'index'],
    
    // Autenticação
    'auth/login' => ['AuthController', 'login'],
    'auth/register' => ['AuthController', 'register'],
    'auth/logout' => ['AuthController', 'logout'],
    
    // Configurações
    'settings/language' => ['SettingsController', 'updateLanguage'],
    'settings/theme' => ['SettingsController', 'updateTheme'],
    'export/orders' => ['SettingsController', 'exportOrders'],
    'export/restaurants' => ['SettingsController', 'exportRestaurants'],
    
    // Carrinho
    'cart' => ['CartController', 'index'],
    'cart/add' => ['CartController', 'add'],
    'cart/remove' => ['CartController', 'remove'],
    'cart/checkout' => ['CartController', 'checkout'],
    
    // Restaurante
    'restaurant/dashboard' => ['RestaurantController', 'dashboard'],
    'restaurant/orders' => ['RestaurantController', 'orders'],
    'restaurant/chat' => ['RestaurantController', 'chat'],
    'restaurant/messages' => ['RestaurantController', 'getMessages'],
    
    // Admin
    'admin/dashboard' => ['AdminController', 'dashboard'],
    'admin/orders' => ['AdminController', 'orders'],
    'admin/restaurants' => ['AdminController', 'restaurants'],
    'admin/services' => ['AdminController', 'services'],
    'admin/chat' => ['AdminController', 'chat'],
    
    // Pagamentos
    'payment/select' => ['PaymentController', 'selectMethod'],
    'payment/pix' => ['PaymentController', 'processPix'],
    'payment/credit-card' => ['PaymentController', 'processCreditCard'],
    'payment/boleto' => ['PaymentController', 'generateBoleto'],
    'payment/carne' => ['PaymentController', 'generateCarne'],
    
    // Páginas estáticas
    'terms' => ['HomeController', 'terms'],
    'privacy' => ['HomeController', 'privacy'],
];

// Rota especial para chat admin com ID
if (preg_match('/^admin\/chat\/(\d+)$/', $path, $matches)) {
    $controller = new AdminController();
    $controller->chat($matches[1]);
    exit;
}

// Rota especial para mensagens admin com ID
if (preg_match('/^admin\/messages\/(\d+)$/', $path, $matches)) {
    $controller = new AdminController();
    $controller->getMessages($matches[1]);
    exit;
}

// Rota especial para editar serviço
if (preg_match('/^admin\/services\/edit\/(\d+)$/', $path, $matches)) {
    $controller = new AdminController();
    $controller->editService($matches[1]);
    exit;
}

// Tratar rota vazia
if (empty($path)) {
    $path = '';
}

// Verificar se a rota existe
if (array_key_exists($path, $routes)) {
    $route = $routes[$path];
    $controllerName = $route[0];
    $methodName = $route[1];
    
    // Instanciar o controller e chamar o método
    try {
        if (!class_exists($controllerName)) {
            throw new Exception("Controller {$controllerName} não encontrado");
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            throw new Exception("Método {$methodName} não encontrado no controller {$controllerName}");
        }
        
        $controller->$methodName();
    } catch (Exception $e) {
        // Erro 500
        http_response_code(500);
        if (isset($_GET['debug'])) {
            echo "Erro: " . $e->getMessage();
        } else {
            echo "Erro interno do servidor";
        }
    }
} else {
    // Rota não encontrada - 404
    http_response_code(404);
    include '../app/views/404.php';
}
?>