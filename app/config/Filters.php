<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'auth' => \App\Filters\AuthFilter::class, //add this..
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,
    ];

    public $globals = [
        'before' => [
            'auth' => ['except' => ['login', 'register']],
            // 'honeypot',
            // 'csrf',
        ],
        'after'  => [
            'toolbar',
            // 'honeypot',
        ],
    ];

    public $methods = [];

    public $filters = [
        'auth' => [
            'before' => [
                'user/*', // Apply the auth filter to all user-related routes
            ],
        ],
    ];
}
