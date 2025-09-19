<?php
/**
 * Controller da página inicial
 */
class HomeController {
    
    public function index() {
        $serviceRepo = new ServiceRepository();
        $services = $serviceRepo->findAll();
        
        include '../app/views/home.php';
    }
}
?>