<?php
/**
 * Autoloader personalizado para o projeto
 */

// Carregar Composer se disponível
$composerPaths = [
    __DIR__ . '/../vendor/autoload.php',
    dirname(__DIR__) . '/vendor/autoload.php',
    dirname(dirname(__DIR__)) . '/vendor/autoload.php'
];

foreach ($composerPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        break;
    }
}

// Autoloader para classes do projeto
spl_autoload_register(function ($class) {
    $basePath = dirname(__DIR__);
    $paths = [
        $basePath . '/app/controllers/',
        $basePath . '/app/models/',
        $basePath . '/app/services/',
        $basePath . '/app/repositories/',
        $basePath . '/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Carregar variáveis de ambiente se arquivo existir
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}
?>