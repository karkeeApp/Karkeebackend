<?php
$params = require(__DIR__ . '/../../common/config/params-local.php');

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'language' => 'en',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
     //    'i18n' => [
	    //     'translations' => [
	    //         'app*' => [
	    //             'class' => 'yii\i18n\PhpMessageSource',
	    //             'basePath' => '@common/messages',
	    //             'sourceLanguage' => 'en',
	    //             'fileMap' => [
	    //                 'app' => 'app.php',
	    //                 'app/error' => 'error.php',
	    //             ],
	    //         ],
	    //     ],
	    // ],
	    'urlManagerFrontEnd' => [
            'class'               => 'yii\web\UrlManager',
			'baseUrl'             => $params['frontend.baseUrl'],
            // 'hostInfo'            => $params['backend.protocol'] . '://' . $params['backend.host'],
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\d+>'        =>'<controller>/<action>',
                '<controller:\w+>/<id:\d+>'                     =>'<controller>/view',
                '<controller:\w+>/<action:\w+>'                 =>'<controller>/<action>',    
                // '<controller:[\w\-]+>/<action:[\w\-]+>'         =>'<controller>/<action>',
            ],
        ],	    
    ],
];