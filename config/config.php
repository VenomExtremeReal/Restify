<?php
/**
 * Configurações gerais da aplicação
 */

// Configurar timezone
date_default_timezone_set('America/Sao_Paulo');

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) session_start();

// BASE_URL - detecta automaticamente protocolo
$protocol = 'https'; // Render sempre usa HTTPS
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
} elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $protocol = 'https';
} elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
    $protocol = 'https';
}

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace('\\', '/', dirname($scriptName));
$basePath = rtrim($basePath, '/');

define('BASE_URL', $protocol . '://' . $host . $basePath);
define('APP_NAME', 'Restify');

// Incluir Database primeiro (Singleton)
require_once __DIR__ . '/database.php';

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
