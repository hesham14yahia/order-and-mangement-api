<?php

return [
    'gateways' => [
        'credit_card' => [
            'merchant_id' => env('PAYMENT_CREDIT_CARD_MERCHANT_ID'),
            'secret' => env('PAYMENT_CREDIT_CARD_SECRET'),
        ],
        'paypal' => [
            'client_id' => env('PAYMENT_PAYPAL_CLIENT_ID'),
            'secret' => env('PAYMENT_PAYPAL_SECRET'),
        ],
    ],
];
