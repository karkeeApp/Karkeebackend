<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => 'login',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'app-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
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
                'server/<controller:\w+>/<action:\w+>'          =>'server/<controller>/<action>',
                'server/<controller:\w+>/<action:\w+>/<id:\d+>' =>'server/<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>'        =>'<controller>/<action>',
                '<controller:\w+>/<id:\d+>'                     =>'<controller>/view',
                '<controller:\w+>/<action:\w+>'                 =>'<controller>/<action>',
                'login'                                         =>'site/login',
                'marketing-offer'                               =>'site/marketingoffer',
                'platform-updates'                              =>'site/platformupdates',
                'faq'                                           =>'site/faq',
                'terms'                                         =>'site/terms',
                'mclub-privacy'                                 =>'site/mclub-privacy',
                'p9club-privacy'                                =>'site/p9club-privacy',                
            ],
        ],
        'view' => [
            'title' => 'Members',
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
