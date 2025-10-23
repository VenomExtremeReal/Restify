<?php
require_once '../config/config.php';

echo "Testando HomeController...<br>";

try {
    $controller = new HomeController();
    $controller->index();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>