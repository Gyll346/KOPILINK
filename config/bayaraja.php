<?php

$runtimeConfig = [];
$runtimeFile = storage_path('app/bayaraja_config.json');
if (file_exists($runtimeFile)) {
    $runtimeConfig = json_decode((string) file_get_contents($runtimeFile), true) ?: [];
}

return [
    'base_url' => rtrim($runtimeConfig['base_url'] ?? env('BAYARAJA_BASE_URL', 'https://bayaraja.arwebs.my.id'), '/'),
    'api_key' => $runtimeConfig['api_key'] ?? env('BAYARAJA_API_KEY', ''),
    'qrishook_secret' => $runtimeConfig['qrishook_secret'] ?? env('QRISHOOK_SECRET', ''),
];
