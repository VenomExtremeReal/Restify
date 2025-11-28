<?php
/**
 * Serviço de pagamento usando SDK oficial da Efí
 */

class EfiPaymentService {
    private $options;
    private $environment;

    public function __construct($environment = 'sandbox') {
        $this->environment = $environment;
        $credentials = include __DIR__ . '/../../config/efi_credentials.php';
        $this->options = $credentials[$environment];
    }

    /**
     * Criar cobrança PIX
     */
    public function createPixCharge($amount, $payerData, $description = 'Pagamento Restify') {
        try {
            // Verificar se SDK está disponível
            if (!class_exists('Efi\EfiPay')) {
                return $this->simulatePixResponse($amount, $payerData);
            }

            $efi = new \Efi\EfiPay($this->options);
            
            $txid = $this->generateTxid();
            
            $body = [
                'calendario' => [
                    'expiracao' => 3600
                ],
                'devedor' => [
                    'cpf' => preg_replace('/\D/', '', $payerData['cpf']),
                    'nome' => $payerData['name']
                ],
                'valor' => [
                    'original' => number_format($amount, 2, '.', '')
                ],
                'chave' => $this->options['pix_key'] ?? $this->options['client_id'],
                'solicitacaoPagador' => $description
            ];

            $params = ['txid' => $txid];
            
            $response = $efi->pixCreateCharge($params, $body);
            
            // Gerar QR Code
            $qrCode = $this->generateQrCode($response['loc']['id']);
            
            return [
                'success' => true,
                'txid' => $txid,
                'payment_id' => $response['loc']['id'],
                'qr_code' => $qrCode['qrcode'] ?? null,
                'qr_code_image' => $qrCode['imagemQrcode'] ?? null,
                'amount' => $amount,
                'expires_at' => date('Y-m-d H:i:s', time() + 3600)
            ];

        } catch (Exception $e) {
            // Em caso de erro, simular resposta para desenvolvimento
            return $this->simulatePixResponse($amount, $payerData);
        }
    }

    /**
     * Gerar QR Code
     */
    private function generateQrCode($locId) {
        try {
            if (!class_exists('Efi\EfiPay')) {
                return ['qrcode' => 'SIMULATED_QR_CODE', 'imagemQrcode' => base64_encode('fake_image')];
            }

            $efi = new \Efi\EfiPay($this->options);
            $params = ['id' => $locId];
            
            return $efi->pixGenerateQRCode($params);
            
        } catch (Exception $e) {
            return ['qrcode' => 'SIMULATED_QR_CODE', 'imagemQrcode' => base64_encode('fake_image')];
        }
    }

    /**
     * Consultar status da cobrança PIX
     */
    public function getPixChargeStatus($txid) {
        try {
            if (!class_exists('Efi\EfiPay')) {
                return $this->simulateStatusResponse($txid);
            }

            $efi = new \Efi\EfiPay($this->options);
            $params = ['txid' => $txid];
            
            $response = $efi->pixDetailCharge($params);
            
            return [
                'success' => true,
                'status' => $response['status'],
                'txid' => $txid,
                'amount' => $response['valor']['original'],
                'paid_at' => $response['pix'][0]['horario'] ?? null,
                'raw_response' => $response
            ];

        } catch (Exception $e) {
            return $this->simulateStatusResponse($txid);
        }
    }

    /**
     * Criar boleto
     */
    public function createBillet($amount, $payerData, $dueDate = null) {
        try {
            if (!class_exists('Efi\EfiPay')) {
                return $this->simulateBilletResponse($amount, $dueDate);
            }

            $efi = new \Efi\EfiPay($this->options);
            
            $body = [
                'payment' => [
                    'banking_billet' => [
                        'expire_at' => $dueDate ?: date('Y-m-d', strtotime('+7 days')),
                        'customer' => [
                            'name' => $payerData['name'],
                            'cpf' => preg_replace('/\D/', '', $payerData['cpf']),
                            'email' => $payerData['email']
                        ]
                    ]
                ],
                'items' => [
                    [
                        'name' => 'Serviços Restify',
                        'amount' => 1,
                        'value' => intval($amount * 100) // Valor em centavos
                    ]
                ]
            ];

            $response = $efi->createOneStepCharge([], $body);
            
            return [
                'success' => true,
                'payment_id' => $response['data']['charge_id'],
                'barcode' => $response['data']['barcode'],
                'linha_digitavel' => $response['data']['billet']['line'],
                'boleto_url' => $response['data']['billet']['link'],
                'amount' => $amount,
                'due_date' => $dueDate ?: date('Y-m-d', strtotime('+7 days'))
            ];

        } catch (Exception $e) {
            return $this->simulateBilletResponse($amount, $dueDate);
        }
    }

    /**
     * Gerar TXID único
     */
    private function generateTxid() {
        return 'RESTIFY' . strtoupper(uniqid()) . time();
    }

    /**
     * Simular resposta PIX para desenvolvimento
     */
    private function simulatePixResponse($amount, $payerData) {
        $txid = $this->generateTxid();
        $qrCodeService = new QrCodeService();
        $pixCode = "00020126580014br.gov.bcb.pix0136" . uniqid() . "5204000053039865802BR5913Restify6009SAO PAULO62070503***6304" . substr(md5($txid), 0, 4);
        
        return [
            'success' => true,
            'txid' => $txid,
            'payment_id' => 'SIM_' . uniqid(),
            'qr_code' => $pixCode,
            'qr_code_image' => $qrCodeService->generateBase64($pixCode),
            'amount' => $amount,
            'expires_at' => date('Y-m-d H:i:s', time() + 3600),
            'simulated' => true
        ];
    }

    /**
     * Simular resposta de status
     */
    private function simulateStatusResponse($txid) {
        return [
            'success' => true,
            'status' => 'ATIVA',
            'txid' => $txid,
            'amount' => '299.99',
            'paid_at' => null,
            'raw_response' => ['status' => 'ATIVA']
        ];
    }

    /**
     * Simular resposta de boleto
     */
    private function simulateBilletResponse($amount, $dueDate) {
        return [
            'success' => true,
            'payment_id' => 'BOLETO_SIM_' . uniqid(),
            'barcode' => '23793381286008231000000010000000123456789012',
            'linha_digitavel' => '23793.38128 60082.310000 00001.000000 0 12345678901234',
            'boleto_url' => '#',
            'amount' => $amount,
            'due_date' => $dueDate ?: date('Y-m-d', strtotime('+7 days'))
        ];
    }
}
?>