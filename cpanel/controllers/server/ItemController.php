<?php
namespace cpanel\controllers\server;

use yii\filters\AccessControl;

class ItemController extends \common\controllers\cpanel\server\ItemController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'update', 'approve', 'redeem', 'delete',
                ],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'update', 'approve', 'redeem', 'delete'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    

}