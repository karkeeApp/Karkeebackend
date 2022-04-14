<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class AppController extends \common\controllers\api\Controller
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
                    return ($username == Yii::$app->params['api.mclub.authentication']['username'] AND $password == Yii::$app->params['api.mclub.authentication']['password']) ? new \common\models\User:NULL;
                },
            ]
        ];
    }

    public function actionEndpoint()
    {
        $club     = Yii::$app->request->get('c');
        $version     = Yii::$app->request->get('v');
        $environment = Yii::$app->request->get('e');

        switch ($club) {
            case 'mclub':
                    $versions = Yii::$app->params['mclub.version'];
                    $endpoint = Yii::$app->params['api.mclub.endpoint'];
                    $auth     = Yii::$app->params['api.mclub.authentication'];
                break;
            case 'p9club':
                    $versions = Yii::$app->params['p9club.version'];
                    $endpoint = Yii::$app->params['api.mclub.endpoint'];
                    $auth     = Yii::$app->params['api.p9club.authentication'];
                break;
            
            default:
                    $versions = NULL;
                    $auth     = NULL;
                    $endpoint = NULL;
                break;
        }

        if (!$versions){
            return [
                'code'    => self::CODE_ERROR, 
                'message' => 'Club not found',
            ];
        }


        if (!isset($versions[$environment]) OR !isset($versions[$environment][$version])){
            return [
                'code'    => self::CODE_ERROR, 
                'message' => 'Version not found',
            ];
        } elseif($versions[$environment][$version]['expire']){
            return [
                'code'    => self::CODE_ERROR, 
                'message' => 'A new release has been published, Please download the new version.',
            ];        
        }

        $status = $versions[$environment][$version]['status'];

        $account_id = ($status == 'dev') ? $auth['dev_account_id'] : $auth['account_id'];
        $hash_id    = ($status == 'dev') ? $auth['dev_hash_id'] : $auth['hash_id'];

        return [
            'code'       => self::CODE_SUCCESS, 
            'endpoint'   => $endpoint[$status],
            'account_id' => $account_id,
            'hash_id'    => $hash_id,
        ];
    }

}