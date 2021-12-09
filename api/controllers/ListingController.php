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