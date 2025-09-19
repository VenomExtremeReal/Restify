<?php
/**
 * Arquivo principal da aplicação Restify
 * Sistema de roteamento simples
 */

require_once '../config/config.php';

// Obter a URL requisitada
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remover o diretório base se necessário
$basePath = '/RestifyApp/public';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Remover barra inicial se existir
$path = ltrim($path, '/');

// Definir rotas
$routes = [
    '' => ['HomeController', 'index'],
    'home' => ['HomeController', 'index'],
    
    // Autenticação
    'auth/login' => ['AuthController', 'login'],
    'auth/register' => ['AuthController', 'register'],
    'auth/logout' => ['AuthController', 'logout'],
    
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

// Verificar se a rota existe
if (array_key_exists($path, $routes)) {
    $route = $routes[$path];
    $controllerName = $route[0];
    $methodName = $route[1];
    
    // Instanciar o controller e chamar o método
    try {
        $controller = new $controllerName();
        $controller->$methodName();
    } catch (Exception $e) {
        // Erro 500
        http_response_code(500);
        echo "Erro interno do servidor";
    }
} else {
    // Rota não encontrada - 404
    http_response_code(404);
    include '../app/views/404.php';
}
?>