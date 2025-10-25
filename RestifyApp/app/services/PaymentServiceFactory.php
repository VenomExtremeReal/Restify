<?php
/**
 * Factory Pattern - Criação de serviços de pagamento
 */
class PaymentServiceFactory {
    
    /**
     * Criar serviço de pagamento baseado no tipo
     */
    public static function create($type, $environment = 'sandbox') {
        switch (strtolower($type)) {
            case 'efi':
            case 'pix':
                require_once 'EfiPaymentService.php';
                return new EfiPaymentService($environment);
                
            case 'mock':
            case 'test':
                return new MockPaymentService();
                
            default:
                throw new InvalidArgumentException("Tipo de pagamento não suportado: $type");
        }
    }
    
    /**
     * Listar tipos disponíveis
     */
    public static function getAvailableTypes() {
        return ['efi', 'pix', 'mock', 'test'];
    }
}

/**
 * Mock Payment Service para testes
 */
class MockPaymentService {
    public function createPixCharge($amount, $payer, $description) {
        return [
            'success' => true,
            'payment_id' => 'MOCK_' . uniqid(),
            'qr_code' => '00020126580014BR.GOV.BCB.PIX0136MOCK_PAYMENT',
            'amount' => $amount,
            'expires_at' => date('Y-m-d H:i:s', time() + 3600)
        ];
    }
    
    public function createBillet($amount, $payer, $dueDate = null) {
        return [
            'success' => true,
            'payment_id' => 'MOCK_BOLETO_' . uniqid(),
            'barcode' => '23793381286008231000000010000000123456789012',
            'amount' => $amount,
            'due_date' => $dueDate ?: date('Y-m-d', strtotime('+7 days'))
        ];
    }
}
?>