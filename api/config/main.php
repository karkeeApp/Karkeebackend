<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'enableCsrfValidation' => FALSE,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
            'loginUrl' => 'login',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function($session){
                return [
                    'user_id' => Yii::$app->user->id
                ];
            }
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
            'class' => '\common\lib\ErrorHandler',
        ],
        'response' => [
            'format' =>  \yii\web\Response::FORMAT_JSON,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            // 'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:.*?>/<action:.*?>/<id:\d+>'        =>'<controller>/<action>',
                '<controller:.*?>/<id:\d+>'                     =>'<controller>/view',
                '<controller:.*?>/<action:\w+>'                 =>'<controller>/<action>',
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'view' => [
            'title' => 'Members Api',
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
