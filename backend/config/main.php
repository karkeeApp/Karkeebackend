<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'defaultRoute' => 'site',
    'components' => [
        'assetManager' => [
            'forceCopy' => FALSE,
        ],
        'request' => [
            'csrfParam' => '_csrf-common',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => 'login',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'app-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['carkee'],
                    'logFile' => '@app/runtime/logs/carkee.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['membership-near-expiry'],
                    'logFile' => '@app/runtime/logs/membership-near-expiry.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login/<company:.*?>'                             =>'site/login',
                'server/<controller:.*?>/<action:.*?>/<id:\d+>' =>'server/<controller>/<action>',
                'server/<controller:.*?>/<action:\w+>'          =>'server/<controller>/<action>',
                '<controller:.*?>/<action:.*?>/<id:\d+>'        =>'<controller>/<action>',
                '<controller:.*?>/<id:\d+>'                     =>'<controller>/view',
                '<controller:.*?>/<action:\w+>'                 =>'<controller>/<action>',
            ],
        ],
        'view' => [
            'title' => 'Clubs',
            'theme' => [
                'pathMap' => [
                     '@app/views' => '@app/views',
                ],
               'baseUrl' => URL . '',
            ],
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\razor\RazorViewRenderer',
                ],
            ],
        ], 
    ],
    'params' => $params,
];
