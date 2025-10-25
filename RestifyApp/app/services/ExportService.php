<?php
/**
 * Strategy Pattern - Estratégias de exportação
 */

interface ExportStrategy {
    public function export($data, $filename);
}

class CsvExportStrategy implements ExportStrategy {
    public function export($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
}

class JsonExportStrategy implements ExportStrategy {
    public function export($data, $filename) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '.json"');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

class XmlExportStrategy implements ExportStrategy {
    public function export($data, $filename) {
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . $filename . '.xml"');
        
        $xml = new SimpleXMLElement('<data/>');
        
        foreach ($data as $item) {
            $record = $xml->addChild('record');
            foreach ($item as $key => $value) {
                $record->addChild($key, htmlspecialchars($value));
            }
        }
        
        echo $xml->asXML();
        exit;
    }
}

/**
 * Context - Serviço de exportação usando Strategy
 */
class ExportService {
    private $strategy;
    private $db;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->connect();
    }
    
    public function setStrategy(ExportStrategy $strategy) {
        $this->strategy = $strategy;
    }
    
    public function export($data, $filename) {
        if (!$this->strategy) {
            throw new Exception('Estratégia de exportação não definida');
        }
        
        $this->strategy->export($data, $filename);
    }
    
    public function exportOrdersCSV($restaurantId = null) {
        $this->setStrategy(new CsvExportStrategy());
        
        $sql = "SELECT o.id, r.name as restaurant, o.total_amount, o.status, o.created_at FROM orders o JOIN restaurants r ON o.restaurant_id = r.id";
        $params = [];
        
        if ($restaurantId) {
            $sql .= " WHERE o.restaurant_id = ?";
            $params[] = $restaurantId;
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->export($orders, 'orders_' . date('Y-m-d'));
    }
    
    public function exportRestaurantsCSV() {
        $this->setStrategy(new CsvExportStrategy());
        
        $stmt = $this->db->prepare("SELECT name, email, whatsapp, address, created_at FROM restaurants ORDER BY created_at DESC");
        $stmt->execute();
        $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->export($restaurants, 'restaurants_' . date('Y-m-d'));
    }
}
?>