<?php

return [
    'paths' => ['*'], // Разрешаем все пути

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'https://localhost:3000',
        'https://127.0.0.1:3000'
    ],

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'X-XSRF-TOKEN',
        'X-CSRF-TOKEN',
        'Accept',
        'Origin'
    ],

    'exposed_headers' => ['*'],

    'max_age' => 0,

    'supports_credentials' => true,
];
