<?php
/**
 * Credenciais e configurações da Efí Bank
 */

return [
    // Credenciais de produção
    'production' => [
        'client_id' => '3ff9011eddcd3a6531f2a2dcbd43a3fd052cb88b',
        'client_secret' => '4d6b2d9862340489b9a95482d0e491f8c2eca651',
        'certificate' => __DIR__ . '/certificates/production.p12', // Certificado de produção
        'sandbox' => false,
        'debug' => false,
        'timeout' => 30
    ],
    
    // Credenciais de homologação/sandbox
    'sandbox' => [
        'client_id' => '3ff9011eddcd3a6531f2a2dcbd43a3fd052cb88b',
        'client_secret' => '4d6b2d9862340489b9a95482d0e491f8c2eca651',
        'certificate' => __DIR__ . '/certificates/sandbox.p12', // Certificado de sandbox
        'sandbox' => true,
        'debug' => true,
        'timeout' => 30
    ]
];
?>