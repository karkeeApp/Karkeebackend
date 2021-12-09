<?php
namespace apicarkee\controllers;

use Yii;

class MemberController extends \common\controllers\apicarkee\MemberController
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
                    'register'                      => ['post'],
                    'register-vendor'               => ['post'],
                    'renewal'                       => ['post'],
                    
                    'login'                         => ['post'],
                    'login-uiid'                    => ['post'],
                    'login-biometric'               => ['post'],
                    'login-face-id'                 => ['post'],

                    'update-password'               => ['post'],
                    'update-email'                  => ['post'],
                    'update-mobile'                 => ['post'],
                    'update-pin'                    => ['post'],
                    'update-profile'                => ['post'],
                    'update-personal-profile'       => ['post'],
                    'update-vendor-profile'         => ['post'],
                    'update-vehicle'                => ['post'],
                    'update-company-profile'        => ['post'],
                    'update-is-premium'             => ['post'],
                    'update-premium-status'         => ['post'],
                    'update-company-onboarding'     => ['post'],
                    'update-director'               => ['post'],
                    'upload-doc'                    => ['post'],

                    'verify-password'               => ['post'],
                    
                    'forgot-password'               => ['post'],
                    'forgot-password-confirm-code'  => ['post'],
                    'forgot-password-update'        => ['post'],                    

                    'add-director'                  => ['post'],

                    'delete-director'               => ['post'],

                    'brand-synopsis'                => ['post'],

                    'social-media-check'            => ['post'],
                    'validate-sm-login'             => ['post'],

                    'sign-in-codes'                 => ['post'],
                    'request-new-club'              => ['post'],
                    'club-registration'             => ['post'],

                    'apple-user-check-redirect'     => ['get'],
                    'info'                          => ['get'],  
                    'redeem-list'                   => ['get'],
                    'doc'                           => ['get'],
                    'options'                       => ['get'],
                    'update-topic'                  => ['get'],
                    'check-admin'                   => ['get'],
                    'logo'                          => ['get'],

                    'renewal-attachment'            => ['get'],
                    'renewal-log-card'              => ['get'],
                    'security-questions'            => ['get'],
                    'file-security-answers'         => ['get'],
                    'list-security-answers'         => ['get'],

                    'main'                          => ['get'],
                    'sub'                           => ['get'],
                    'super'                         => ['get']
                    // 'dashboard'                     => ['get']
	            ],
    		],
            'authenticator' => [
	            'except' => [
                                'main','sub','super', 'register', 'register-vendor', 'login', 'login-uiid', 'login-biometric', 'login-face-id', 
                                'forgot-password', 'forgot-password-confirm-code', 'forgot-password-update','doc','social-media-check',
                                'apple-user-check-redirect','logo','file-security-answers','sign-in-codes','renewal-attachment'
                            ],
                // 'optional' => ['club-registration'],
                'class' => \yii\filters\auth\CompositeAuth::class,
                'authMethods' => [
                    \yii\filters\auth\HttpBasicAuth::class,
                    \yii\filters\auth\HttpBearerAuth::class,
                    \yii\filters\auth\QueryParamAuth::class,
                ]            
            ]
        ];
    }


}
