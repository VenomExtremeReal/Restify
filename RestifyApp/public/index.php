<?php
/**
 * Arquivo principal da aplicação Restify
 * Sistema de roteamento simples
 */

require_once '../config/config.php';
require_once '../config/i18n.php';

// Inicializar idioma
$language = $_SESSION['language'] ?? $_COOKIE['language'] ?? 'pt';
I18n::init($language);

// Obter a URL requisitada
$request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$path = parse_url($request, PHP_URL_PATH);

// Garantir que $path não seja null
if ($path === null) {
    $path = '/';
}

// Detectar o diretório base automaticamente
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName !== '/' && $scriptName !== '\\' && strpos($path, $scriptName) === 0) {
    $path = substr($path, strlen($scriptName));
}

// Remover barra inicial se existir
$path = ltrim($path, '/');

// Debug para desenvolvimento
if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
    echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
    echo "PATH: " . $path . "\n";
    echo "BASE_URL: " . BASE_URL . "\n";
    echo "</pre>";
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
    'auth/forgot-password' => ['AuthController', 'forgotPassword'],
    'auth/reset-password' => ['AuthController', 'resetPassword'],
    
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
if (empty($path) || $path === '/' || $path === 'index.php') {
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