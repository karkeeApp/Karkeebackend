<?php
namespace apicarkee\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class SiteController extends \common\controllers\apicarkee\admin\SiteController
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
                    'update-default-settings'   => ['post'],
                    'data-protection-terms' => ['get'],
                    'p9club-terms' => ['get'],
                    'fb-whatsapp-gc-rules' => ['get'],
                    'settings' => ['get']
	            ],
    		],
            'authenticator' => [
                'except' => ['data-protection-terms', 'p9club-terms','fb-whatsapp-gc-rules','settings'],
                'class' => \yii\filters\auth\CompositeAuth::class,
                'authMethods' => [
                        \yii\filters\auth\HttpBasicAuth::class,
                        \yii\filters\auth\HttpBearerAuth::class,
                        \yii\filters\auth\QueryParamAuth::class,
                ],             
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['data-protection-terms', 'p9club-terms','fb-whatsapp-gc-rules'],
                'formats' => [
                    'text/html' => yii\web\Response::FORMAT_HTML
                ]
            ],
        ];
    }
}