<?php

return [
    'paths' => ['*'],

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
        'Set-Cookie',
        'Referer',  // Добавляем для некоторых браузеров
        'User-Agent',
        'Access-Control-Allow-Origin',
        'Access-Control-Allow-Headers',
        'Access-Control-Allow-Methods',
        'Access-Control-Allow-Credentials',
        'iyssu_backend_session'
    ],

    'exposed_headers' => [
        'Set-Cookie',
        'X-XSRF-TOKEN',  // Важно для CSRF токена
        'iyssu_backend_session',
        'Cookie'
    ],

    'max_age' => 7200,

    'supports_credentials' => true,

    // access_control_allow_credentials не нужен, так как supports_credentials уже есть
    // 'access_control_allow_credentials' => true,
];
