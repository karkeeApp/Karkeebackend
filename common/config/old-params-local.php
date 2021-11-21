<?php
return [
    'frontend.baseUrl' => 'http://client.carkee.sg/',
    'environment'      => 'production',
    'admin.email'      => 'admin@carkee.sg',
    'froala-key' => 'jUA1yB1B3D4C3E1A1pnbzrD1qrfB-7srB3jD-11gC3E3G3B2B7A4E2F4C2F3==',
    'test_emails' => [
    ],
    'test_emails_format' => [
        '/@yopmail\.com/'
    ],

    'api.carkee.endpoint' => [
        'prod' => 'http://carkeeapi.carkee.sg',
        'dev' => 'http://qa.carkeeapi.carkee.sg',
    ],

    'api.carkee.authentication' => [
        'username' => 'naruto',
        'password' => 'bvM9pmEEQ7tfPZdC'
    ],

    'api.mclub.endpoint' => [
        'prod'       => 'http://api.carkee.sg/',
        'dev'        => 'http://qa.api.carkee.sg/',
    ],
    'api.mclub.authentication' => [
        'username' => 'sasuke',
        'password' => 'ZhNnvB79BNc5PzAS',
        'account_id' => 1,
        'hash_id'    => '1595920778',
        'dev_account_id' => 8,
        'dev_hash_id'    => '987654321',
    ],
    'api.p9club.authentication' => [
        'username' => 'sasuke',
        'password' => 'ZhNnvB79BNc5PzAS',
        'account_id' => 2,
        'hash_id'    => '1595920779',
        'dev_account_id' => 9,
        'dev_hash_id'    => '123456789',
    ],
    'carkee.version' => [
        'ios' => [
            '1.0.12' => ['status' => 'prod', 'expire' => false],
            '1.0.1' => ['status' => 'dev', 'expire' => false],
            '1.0.0' => ['status' => 'dev', 'expire' => false],
        ],
        'android' => [
            '1.0.12' => ['status' => 'prod', 'expire' => false],
            '1.0.1' => ['status' => 'dev', 'expire' => false],
            '1.0.0' => ['status' => 'dev', 'expire' => false],
        ]
    ],

    'mclub.version' => [
        'ios' => [
            '9.9.9' => ['status' => 'dev', 'expire' => false],
            '1.0.12' => ['status' => 'prod', 'expire' => false],

            '1.0.10' => ['status' => 'dev', 'expire' => false],
            '1.0.9' => ['status' => 'prod', 'expire' => false],
            '1.0.8' => ['status' => 'prod', 'expire' => true],
            '1.0.7' => ['status' => 'prod', 'expire' => true],
            '1.0.6' => ['status' => 'prod', 'expire' => true],
            '1.0.5' => ['status' => 'prod', 'expire' => true],
            '1.0.4' => ['status' => 'prod', 'expire' => true],
            '1.0.3' => ['status' => 'prod', 'expire' => true],
            '1.0.2' => ['status' => 'prod', 'expire' => true],
            '1.0.1' => ['status' => 'prod', 'expire' => true],
            '1.0.0' => ['status' => 'dev', 'expire' => true],
        ],
        'android' => [
            '9.9.9' => ['status' => 'dev', 'expire' => false],

            '1.0.12' => ['status' => 'prod', 'expire' => false],
            '1.0.11' => ['status' => 'prod', 'expire' => false],
            '1.0.10' => ['status' => 'prod', 'expire' => true],
            '1.0.9' => ['status' => 'prod', 'expire' => true],
            '1.0.8' => ['status' => 'prod', 'expire' => true],
            '1.0.7' => ['status' => 'prod', 'expire' => true],
            '1.0.6' => ['status' => 'prod', 'expire' => true],
            '1.0.5' => ['status' => 'prod', 'expire' => true],
            '1.0.4' => ['status' => 'prod', 'expire' => true],
            '1.0.3' => ['status' => 'prod', 'expire' => true],
            '1.0.2' => ['status' => 'prod', 'expire' => true],
            '1.0.1' => ['status' => 'prod', 'expire' => true],
            '1.0.0' => ['status' => 'prod', 'expire' => true],
        ]
    ],
    'p9club.version' => [
        'ios' => [            
            '9.9.9' => ['status' => 'prod', 'expire' => false],

            '1.0.12' => ['status' => 'prod', 'expire' => false],
            '1.0.3' => ['status' => 'dev', 'expire' => false],
            '1.0.2' => ['status' => 'prod', 'expire' => false],
            '1.0.1' => ['status' => 'prod', 'expire' => false],
            '1.0.0' => ['status' => 'prod', 'expire' => false],
        ],
        'android' => [            
            '9.9.9' => ['status' => 'prod', 'expire' => false],

            '1.0.12' => ['status' => 'prod', 'expire' => false],
            '1.0.3' => ['status' => 'dev', 'expire' => false],
            '1.0.2' => ['status' => 'prod', 'expire' => false],
            '1.0.1' => ['status' => 'prod', 'expire' => false],
            '1.0.0' => ['status' => 'prod', 'expire' => false],
        ]
    ],

];
