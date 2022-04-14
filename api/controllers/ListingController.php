<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class ListingController extends \common\controllers\api\ListingController
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
                    'add'         => ['post'],
                    'edit'        => ['post'],
                    'delete'      => ['post'],
                    'redeem'      => ['post'],
                    'info'        => ['get'],
                    'view-by-id'  => ['get'],
                    'list'        => ['get'],
                    'redeem-list' => ['get'],
                    'list-all'    => ['get'],
                    'featured'    => ['get'],
                    'gallery'     => ['get'],
                ],
            ],
            // 'contentNegotiator' => [
            //     'class' => \yii\filters\ContentNegotiator::class,
            //     'only' => ['add', 'edit', 'redeem', 'info', 'list', 'redeem-list', 'list-all'],
            //     'formats' => [
            //         'application/json' => Response::FORMAT_JSON
            //     ]
            // ],
            'authenticator' => [
                'optional' => ['featured', 'gallery','list-all'],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }


}