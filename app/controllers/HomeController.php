<?php
/**
 * Controller da página inicial
 */
class HomeController {
    
    public function index() {
        try {
            $serviceRepo = new ServiceRepository();
            $services = $serviceRepo->findAll();
            
            if (empty($services)) {
                $services = [];
            }
            
            include '../app/views/home.php';
        } catch (Exception $e) {
            error_log("Erro na página inicial: " . $e->getMessage());
            $services = [];
            include '../app/views/home.php';
        }
    }
    
    public function terms() {
        include '../app/views/terms.php';
    }
    
    public function privacy() {
        include '../app/views/privacy.php';
    }
}
?>