<?php
/**
 * Autoloader personalizado para o projeto
 */

// Carregar Composer se disponível
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerAutoload)) require_once $composerAutoload;

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


?>