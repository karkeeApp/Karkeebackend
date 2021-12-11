<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-cpanel',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'cpanel\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'defaultRoute' => 'site',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-common',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-cpanel', 'httpOnly' => true],
            'loginUrl' => 'login',
        ],
        // 'user' => [
        //     'identityClass' => 'common\models\Admin',
        //     'enableAutoLogin' => true,
        //     'identityCookie' => ['name' => '_identity-cpanel', 'httpOnly' => true],
        //     'loginUrl' => 'login',
        // ],
        'session' => [
            // this is the name of the session cookie used for login on the cpanel
            'name' => 'app-cpanel',
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
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'view' => [
            'title' => 'CARKEE',
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
