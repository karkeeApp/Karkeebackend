<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 25/04/2021
 * Time: 5:30 PM
 */

namespace apicarkee\controllers;

use Yii;
class SponsorController extends \common\controllers\apicarkee\SponsorController
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
                    'list' => ['get'],
                ],
            ],
            'authenticator' => [
                'except' => ['list'],
                'class' => \yii\filters\auth\QueryParamAuth::class,
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['list'],
                'formats' => [
                    'text/html' => yii\web\Response::FORMAT_JSON
                ]
            ],

        ];
    }
}