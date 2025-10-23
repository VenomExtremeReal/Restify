<?php
class ExportService {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    public function exportOrdersCSV($restaurantId = null) {
        $sql = "
            SELECT o.id, r.name as restaurant, o.total_amount, o.status, o.created_at 
            FROM orders o 
            JOIN restaurants r ON o.restaurant_id = r.id
        ";
        
        $params = [];
        if ($restaurantId) {
            $sql .= " WHERE o.restaurant_id = ?";
            $params[] = $restaurantId;
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.csv"');
        header('Cache-Control: no-cache, must-revalidate');
        
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['ID', 'Restaurante', 'Total', 'Status', 'Data']);
        
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['id'],
                $order['restaurant'],
                number_format($order['total_amount'], 2, ',', '.'),
                $order['status'],
                date('d/m/Y H:i', strtotime($order['created_at']))
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    public function exportRestaurantsCSV() {
        $stmt = $this->db->prepare("SELECT name, email, whatsapp, address, created_at FROM restaurants ORDER BY created_at DESC");
        $stmt->execute();
        $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="restaurants_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nome', 'E-mail', 'WhatsApp', 'Endereço', 'Data Cadastro']);
        
        foreach ($restaurants as $restaurant) {
            fputcsv($output, [
                $restaurant['name'],
                $restaurant['email'],
                $restaurant['whatsapp'],
                $restaurant['address'],
                date('d/m/Y H:i', strtotime($restaurant['created_at']))
            ]);
        }
        
        fclose($output);
    }
}
?>