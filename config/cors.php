<?php

return [
    'paths' => ['*'], // Разрешаем все пути временно для тестирования

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:3000',
        'https://localhost:3000',
        'http://127.0.0.1:3000',
        'https://127.0.0.1:3000',
        'https://iyssu-backend-main-iigq56.laravel.cloud'
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-XSRF-TOKEN',
        'X-CSRF-TOKEN',
        'Origin',
        'Cookie',
        'Set-Cookie'
    ],

    'exposed_headers' => ['Set-Cookie'],

    'max_age' => 7200,

    'supports_credentials' => true,

    'access_control_allow_credentials' => true,
];
