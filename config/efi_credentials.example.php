<?php
/**
 * Credenciais e configurações da Efí Bank
 * 
 * INSTRUÇÕES:
 * 1. Copie este arquivo para: efi_credentials.php
 * 2. Preencha com suas credenciais reais
 * 3. NUNCA commite o arquivo efi_credentials.php
 */

return [
    // Credenciais de produção
    'production' => [
        'client_id' => 'SEU_CLIENT_ID_PRODUCAO',
        'client_secret' => 'SEU_CLIENT_SECRET_PRODUCAO',
        'certificate' => __DIR__ . '/certificates/production.p12',
        'pix_key' => 'sua.chave@pix.com.br',  // Chave PIX cadastrada na Efí
        'sandbox' => false,
        'debug' => false,
        'timeout' => 30
    ],
    
    // Credenciais de homologação/sandbox
    'sandbox' => [
        'client_id' => 'SEU_CLIENT_ID_SANDBOX',
        'client_secret' => 'SEU_CLIENT_SECRET_SANDBOX',
        'certificate' => __DIR__ . '/certificates/sandbox.p12',
        'pix_key' => 'sua.chave@pix.com.br',  // Chave PIX cadastrada na Efí
        'sandbox' => true,
        'debug' => true,
        'timeout' => 30
    ]
];
?>
