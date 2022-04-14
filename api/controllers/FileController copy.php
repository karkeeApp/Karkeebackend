<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class FileController extends \common\controllers\api\FileController
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
                    'doc'=> ['get'],
                    'identity'=> ['get'],
                    'media'=> ['get'],
                    'mediathumb'=> ['get'],
                    'staffimport'=> ['get'],
                    'download'=> ['get'],
                    'banner'=> ['get'],
                    'ads'=> ['get'],
                    'news'=> ['get'],
                    'event-gallery'=> ['get'],
                    'news-gallery'=> ['get'],
                    'event'       => ['get'],
                    'news'        => ['get'],
                    'payment'     => ['get'],
                    'log-card'    => ['get']
                ],
            ],
            'authenticator' => [
                'optional' => ['doc','identity','media','mediathumb','staffimport','download','event-gallery','event','banner','ads','news','news-gallery','payment','log-card'],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }
}