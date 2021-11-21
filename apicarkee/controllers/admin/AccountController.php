<?php
namespace apicarkee\controllers\admin;

use Yii;

class AccountController extends \common\controllers\apicarkee\admin\AccountController
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
                    'index'                     => ['get'],
                    'list'                      => ['get'],
                    'create'                    => ['post'],
                    'delete'                    => ['delete','post'],
                    'hard-delete'               => ['delete','post'],
                    'update'                    => ['put','post'],
                    'view'                      => ['get'],
                    'list-options'              => ['get'],
                    'on-off-ads'                => ['post'],
                    'set-default-expiry'        => ['post'],
                    'set-renewal-reminder'      => ['post'],
                    'skip-member-approval'      => ['post'],
                    'set-club-code'             => ['post'],
                    'set-default-days-unverified'=> ['post'],
                    'update-default-settings'   => ['post'],

                    'add-security-questions'    => ['post'],
                    'edit-security-questions'   => ['post'],
                    'delete-security-questions' => ['post'],
                    'list-security-questions'   => ['get'],
                    'view-security-questions'   => ['get'],

                    'account-by-club-code'      => ['get'],
                    'questions-by-club-code'    => ['get'],

                    'list-account-membership'    => ['get'],
                    'view-account-membership'    => ['get'],
                    'account-membership-approve' => ['post'],
                    'account-membership-reject'  => ['post'],
	            ],
    		],
            'authenticator' => [
                'optional' => ['index','list','list-options'],
                'class' => \yii\filters\auth\CompositeAuth::class,
                'authMethods' => [
                        \yii\filters\auth\HttpBasicAuth::class,
                        \yii\filters\auth\HttpBearerAuth::class,
                        \yii\filters\auth\QueryParamAuth::class,
                ],   
            ]  
        ];
    }

    
}