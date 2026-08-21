<?php

return [
    'currency' => [
        'symbol' => '$',
        'code' => 'USD',
    ],

    'bank' => [
        'account' => env('BANK_ACCOUNT', 'ES00 0000 0000 0000 0000 0000'),
        'holder' => env('BANK_HOLDER', 'Tu Nombre'),
        'iban' => env('BANK_IBAN', 'ES00 0000 0000 0000 0000 0000'),
        'reference_prefix' => env('BANK_REFERENCE_PREFIX', 'FOTOS'),
    ],
];
