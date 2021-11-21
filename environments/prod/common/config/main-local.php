<?php
define('MOD_COUNT', 1);

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=db_carkee',
            'username' => 'fredcarz',
            'password' => 'fdc101082',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'assetManager' => [
            'forceCopy' => TRUE,
        ],
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/frontend/web',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],    
    ],
];
