<?php
class SettingsController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    public function updateLanguage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $language = $_POST['language'] ?? 'pt';
            
            if (isset($_SESSION['restaurant_id'])) {
                $stmt = $this->db->prepare("UPDATE restaurants SET language = ? WHERE id = ?");
                $stmt->execute([$language, $_SESSION['restaurant_id']]);
            }
            
            $_SESSION['language'] = $language;
            setcookie('language', $language, time() + (86400 * 30), '/');
        }
        
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
    
    public function updateTheme() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $theme = $_POST['theme'] ?? 'light';
            
            if (isset($_SESSION['restaurant_id'])) {
                $stmt = $this->db->prepare("UPDATE restaurants SET theme = ? WHERE id = ?");
                $stmt->execute([$theme, $_SESSION['restaurant_id']]);
            }
            
            $_SESSION['theme'] = $theme;
            setcookie('theme', $theme, time() + (86400 * 30), '/');
        }
        
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
    
    public function exportOrders() {
        $exportService = new ExportService();
        $restaurantId = isset($_SESSION['restaurant_id']) ? $_SESSION['restaurant_id'] : null;
        $exportService->exportOrdersCSV($restaurantId);
    }
    
    public function exportRestaurants() {
        $exportService = new ExportService();
        $exportService->exportRestaurantsCSV();
    }
}
?>