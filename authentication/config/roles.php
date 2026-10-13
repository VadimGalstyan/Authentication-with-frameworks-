<?php

return [
    'default' => env('DEFAULT_ROLE', 'user'),

    'admin' => [
        'name' => env('ADMIN_NAME', 'Admin'),
        'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        'password' => env('ADMIN_PASSWORD', 'ChangeMe123'),
    ],
];