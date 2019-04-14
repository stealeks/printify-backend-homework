<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'input_key' => 'token',
            'storage_key' => 'token',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Model\Auth\User::class,
        ],
    ],
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],
];
