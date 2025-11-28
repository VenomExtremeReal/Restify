<?php

return [
    'sandbox' => [
        'client_id' => 'demo_client_id',
        'client_secret' => 'demo_client_secret',
        'certificate' => __DIR__ . '/../config/certificates/demo.p12',
        'pix_key' => 'demo@restify.com'
    ],
    'production' => [
        'client_id' => '',
        'client_secret' => '',
        'certificate' => '',
        'pix_key' => ''
    ]
];
