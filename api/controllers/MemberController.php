<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class MemberController extends \common\controllers\api\MemberController
{
	public function behaviors()
    {
    	return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                // 'cors' => [
                //     // restrict access to
                //     'Origin' => ['http://www.myserver.com', 'https://www.myserver.com'],
                //     // Allow only POST and PUT methods
                //     'Access-Control-Request-Method' => ['POST', 'PUT'],
                //     // Allow only headers 'X-Wsse'
                //     'Access-Control-Request-Headers' => ['X-Wsse'],
                //     // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                //     'Access-Control-Allow-Credentials' => true,
                //     // Allow OPTIONS caching
                //     'Access-Control-Max-Age' => 3600,
                //     // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                //     'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                // ],

            ],
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [
                    'register'                     => ['post'],
                    'login'                        => ['post'],
                    'login-uiid'                   => ['post'],
                    'login-biometric'              => ['post'],
                    'login-face-id'                => ['post'],
                    'update-password'              => ['post'],
                    'update-email'                 => ['post'],
                    'update-mobile'                => ['post'],
                    'update-pin'                   => ['post'],
                    'verify-password'              => ['post'],
                    'info'                         => ['get'],  
                    'redeem-list'                  => ['get'],
                    'update-profile'               => ['post'],
                    'upload-doc'                   => ['post'],
                    'doc'                          => ['get'],
                    'renewal-attachment'           => ['get'],
                    'renewal-log-card'             => ['get'],
                    'options'                      => ['get'],
                    'update-personal-profile'      => ['post'],
                    'update-vendor-profile'        => ['post'],
                    'update-vehicle'               => ['post'],
                    
                    'forgot-password'              => ['post'],
                    'forgot-password-confirm-code' => ['post'],
                    'forgot-password-update'       => ['post'],
                    
                    'register-vendor'              => ['post'],
                    'renewal'                      => ['post'],

                    'social-media-check'            => ['post'],
                    'validate-sm-login'             => ['post'],
                    'apple-user-check-redirect'     => ['get'],

                    'update-topic'                  => ['get'],
                    'check-admin'                   => ['get'],

                    'request-new-club'              => ['get']
	            ],
    		],
            // 'contentNegotiator' => [
            //     'class' => \yii\filters\ContentNegotiator::class,
            //     'only' => ['register', 'login', 'info', 'update-password', 'update-pin', 'redeem-list', 'update-profile', 'upload-doc', 'options'],
            //     'formats' => [
            //         'application/json' => yii\web\Response::FORMAT_JSON
            //     ]
            // ],
            'authenticator' => [
	            'except' => [
                                'register', 'register-vendor', 'login', 'login-uiid', 'renewal-attachment',
                                'login-biometric', 'login-face-id', 'forgot-password', 'renewal-log-card',
                                'forgot-password-confirm-code', 'forgot-password-update', 'request-new-club',
                                'doc','social-media-check', 'apple-user-check-redirect'
                            ],
	            'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }


}