<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE', 'DS37706'),
    'api_key' => env('DUITKU_API_KEY', '5222dae2a2ecaafc10884ad33bcd5600'),
    'is_sandbox' => (bool) env('DUITKU_IS_SANDBOX', true),
    'payment_method' => env('DUITKU_PAYMENT_METHOD', 'SP'), // ShopeePay QRIS channel
];
