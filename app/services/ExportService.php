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

/**
 * Context - Serviço de exportação usando Strategy
 */
class ExportService {
    private $strategy;
    
    public function setStrategy(ExportStrategy $strategy) {
        $this->strategy = $strategy;
    }
    
    public function export($data, $filename) {
        if (!$this->strategy) {
            throw new Exception('Estratégia de exportação não definida');
        }
        
        $this->strategy->export($data, $filename);
    }
    
    public function exportCSV($data, $filename) {
        $this->setStrategy(new CsvExportStrategy());
        $this->export($data, $filename);
    }
}
?>