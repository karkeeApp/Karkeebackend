<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api-carkee',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'apicarkee\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api-carkee',
            'enableCsrfValidation' => FALSE,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api-carkee', 'httpOnly' => true],
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
                '<controller:.*?>/getAll'                       =>'<controller>/list',
                '<controller:.*?>/<action:.*?>/<id:\d+>'        =>'<controller>/<action>',
                '<controller:.*?>/<id:\d+>'                     =>'<controller>/view',
                '<controller:.*?>/<action:\w+>'                 =>'<controller>/<action>',
                'PUT <controller:[\w-]+>/<id:\d+>'              =>'<controller>/update',
                'DELETE <controller:[\w-]+>/<id:\d+>'           =>'<controller>/delete',
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'view' => [
            'title' => 'Carkee Api',
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
