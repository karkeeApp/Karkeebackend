<?php
namespace apicarkee\controllers\admin;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\UserForm;

use common\models\User;
use common\models\Loan;
use common\helpers\Common;
use common\helpers\HRHelper;
use common\lib\PaginationLib;

class VendorController extends \common\controllers\apicarkee\admin\VendorController
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
                    'add-vendor'        => ['post'],
                    'edit-vendor'       => ['post'],
                    'update'            => ['post'],
                    'loans'             => ['post'],
                    'update-password'   => ['post'],
                    'update-email'      => ['post'],
                    'update-mobile'     => ['post'],
                    'update-settings'   => ['post'],
                    'approve'           => ['post'],
                    'reject'            => ['post'],
                    'itemlist'          => ['get'],
                    'delete'            => ['delete'],
                    'search-by-email'   => ['get'],
                    'convert-to-vendor' => ['post'],
                    'create-vendor'     => ['post'],
                    'search-vendor-name' => ['get'],
                    'vendor-view'       => ['get']
	            ],
    		],
            'authenticator' => [
                'except' => ['index','list'],
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
    //                 'list', 'add-vendor', 'edit-vendor', 'update', 'loans', 'updatepassword', 'updateemail',
    //                 'updatemobile', 'updatesettings', 'approve', 'reject', 'itemlist', 'delete'
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
