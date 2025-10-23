<?php
/**
 * Configurações gerais da aplicação
 */

// Iniciar sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definir constantes dinamicamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Corrigir BASE_URL para servidor de desenvolvimento
if ($scriptName === '/' || $scriptName === '\\') {
    $baseUrl = $protocol . '://' . $host;
} else {
    $baseUrl = $protocol . '://' . $host . $scriptName;
}

define('BASE_URL', $baseUrl);
define('APP_NAME', 'Restify');

// Incluir autoloader simples
spl_autoload_register(function ($class) {
    $paths = [
        '../app/controllers/',
        '../app/models/',
        '../app/services/',
        '../app/repositories/',
        '../config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

/**
 * Função para verificar se usuário está logado
 */
function isLoggedIn() {
    return isset($_SESSION['restaurant_id']) || isset($_SESSION['admin']);
}

/**
 * Função para verificar se é admin
 */
function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

/**
 * Função para redirecionar
 */
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Função para gerar URLs
 */
function url($path = '') {
    return BASE_URL . ($path ? '/' . ltrim($path, '/') : '');
}
?>