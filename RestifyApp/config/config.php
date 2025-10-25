<?php
/**
 * Configurações gerais da aplicação
 */

// Iniciar sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// BASE_URL fixo para desenvolvimento
define('BASE_URL', 'http://localhost/RestifyApp/public');
define('APP_NAME', 'Restify');

// Incluir autoloader personalizado
require_once __DIR__ . '/autoload.php';

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