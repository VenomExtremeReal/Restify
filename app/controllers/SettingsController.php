<?php
/**
 * Controller de configurações
 */
class SettingsController {
    
    public function updateLanguage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $language = $_POST['language'] ?? 'pt';
            
            // Validar idioma
            $validLanguages = ['pt', 'en', 'es'];
            if (!in_array($language, $validLanguages)) {
                $language = 'pt';
            }
            
            $_SESSION['language'] = $language;
            setcookie('language', $language, time() + (86400 * 30), '/');
            
            I18n::setLanguage($language);
        }
        
        redirect('/');
    }
    
    public function updateTheme() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $theme = $_POST['theme'] ?? 'light';
            
            // Validar tema
            $validThemes = ['light', 'dark'];
            if (!in_array($theme, $validThemes)) {
                $theme = 'light';
            }
            
            $_SESSION['theme'] = $theme;
            setcookie('theme', $theme, time() + (86400 * 30), '/');
        }
        
        // Retornar JSON para AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function exportOrders() {
        if (!isLoggedIn()) redirect('/auth/login');
        
        $orderRepo = new OrderRepository();
        
        // Admin exporta todos, restaurante exporta apenas seus pedidos
        $orders = isAdmin() ? $orderRepo->findAll() : $orderRepo->findByRestaurant($_SESSION['restaurant_id']);
        
        if (empty($orders)) {
            echo "Nenhum pedido para exportar";
            exit;
        }
        
        $data = [];
        foreach ($orders as $order) {
            $row = [
                'ID' => $order->id,
                'Total' => 'R$ ' . number_format($order->total_amount, 2, ',', '.'),
                'Status' => $order->status,
                'Status Pagamento' => $order->payment_status ?? 'pending',
                'Data' => $order->created_at
            ];
            
            // Admin vê nome do restaurante
            if (isAdmin()) {
                $row['Restaurante'] = $order->restaurant_name ?? '';
                $row['Email'] = $order->restaurant_email ?? '';
            }
            
            $data[] = $row;
        }
        
        (new ExportService())->exportCSV($data, 'pedidos_' . date('Y-m-d'));
    }
    
    public function exportRestaurants() {
        if (!isAdmin()) redirect('/auth/login');
        
        $restaurants = (new RestaurantRepository())->findAll();
        
        $data = [];
        foreach ($restaurants as $restaurant) {
            if ($restaurant->id != 999) {
                $data[] = [
                    'ID' => $restaurant->id,
                    'Nome' => $restaurant->name,
                    'Email' => $restaurant->email,
                    'WhatsApp' => $restaurant->whatsapp,
                    'Endereço' => $restaurant->address,
                    'Data Cadastro' => $restaurant->created_at
                ];
            }
        }
        
        if (empty($data)) {
            echo "Nenhum restaurante para exportar";
            exit;
        }
        
        (new ExportService())->exportCSV($data, 'restaurantes_' . date('Y-m-d'));
    }
}
?>