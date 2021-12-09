<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class ItemController extends \common\controllers\api\ItemController
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
                    'list'        => ['get'],
                    'redeem-list' => ['get'],
                    'list-all'    => ['get'],
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
                'except' => [],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }


}