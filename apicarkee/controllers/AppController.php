<?php
namespace apicarkee\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class AppController extends \common\controllers\apicarkee\Controller
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
                    'endpoint' => ['get'],
                ],
            ],
            'authenticator' => [
                'except' => ['banner'],
                'class' => \yii\filters\auth\HttpBasicAuth::class,
                'auth' => function($username, $password) {
                    return ($username == Yii::$app->params['api.carkee.authentication']['username'] AND $password == Yii::$app->params['api.carkee.authentication']['password']) ? new \common\models\User:NULL;
                },
            ]
        ];
    }

    public function actionEndpoint()
    {
        $version     = Yii::$app->request->get('v');
        $environment = Yii::$app->request->get('e');

        $carkeeVersion = Yii::$app->params['carkee.version'];

        if (!isset($carkeeVersion[$environment]) OR !isset($carkeeVersion[$environment][$version])){
            return [
                'code'    => self::CODE_ERROR, 
                'message' => 'Not found',
            ];
        } elseif($carkeeVersion[$environment][$version]['expire']){
            return [
                'code'    => self::CODE_ERROR, 
                'message' => 'A new release has been published, Please download the new version.',
            ];
        }

        $status = $carkeeVersion[$environment][$version]['status'];

        return [
            'code'     => self::CODE_SUCCESS, 
            'endpoint' => Yii::$app->params['api.carkee.endpoint'][$status]
        ];
    }

}