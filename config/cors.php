<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // ✅ Ganti: pakai wildcard agar semua port localhost diizinkan
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [
        '#^http://localhost(:\d+)?$#',  // ✅ Izinkan semua port localhost
        '#^http://127\.0\.0\.1(:\d+)?$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // ✅ Pastikan ini false — karena Flutter pakai Bearer Token, bukan cookie
    'supports_credentials' => false,
];