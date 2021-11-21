<?php
namespace apicarkee\controllers\admin;

use Yii;

class SponsorController extends \common\controllers\apicarkee\admin\SponsorController
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

                    'list'              => ['get'],
                    'index'             => ['get'],
                    'create'            => ['post'],
                    'update'            => ['post'],
                    'delete'            => ['delete'],
                    'add-sponsor'       => ['post'],
                    'edit-sponsor'      => ['post'],
                    'silver'            => ['get'],
                    'gold'              => ['get'],
                    'platinum'          => ['get'],
                    'diamond'           => ['get'],
                    'remove-level'      => ['get'],
                    'normal'            => ['get'],
                    'approve'           => ['post'],
                    'reject'            => ['post'],
	            ],
    		],
            'authenticator' => [
                'except' => ['list'],
                'class' => \yii\filters\auth\CompositeAuth::class,
                'authMethods' => [
                        \yii\filters\auth\HttpBasicAuth::class,
                        \yii\filters\auth\HttpBearerAuth::class,
                        \yii\filters\auth\QueryParamAuth::class,
                ],   
            ]  
        ];
    }






    // public function behaviors()
    // {
    //     return [
            
    //         'contentNegotiator' => [
    //             'class' => \yii\filters\ContentNegotiator::class,
    //             'only' => [
    //                 'list','create', 'update', 'delete','add-sponsor', 'edit-sponsor', 'silver','gold','platinum',
    //                 'diamond','remove-level','normal'
    //             ],
    //             'formats' => [
    //                 'application/json' => \yii\web\Response::FORMAT_JSON
    //             ]
    //         ],
    //         'access' => [
    //             'class' => AccessControl::class,
    //             'rules' => [
    //                 [
    //                     'actions' => parent::userActions(),
    //                     'allow' => true,
    //                 ],
    //             ],
    //         ],
    //     ];
    // }
}