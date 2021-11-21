<?php
namespace apicarkee\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class FreebiesController extends \common\controllers\apicarkee\FreebiesController
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
                    'mclub-shirt'  => ['get'],
                ],
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['mclub-shirt'],
                'formats' => [
                    'text/html' => yii\web\Response::FORMAT_HTML
                ]
            ],
            'authenticator' => [
                'except' => ['featured'],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }


}