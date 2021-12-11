<?php
namespace cpanel\controllers\server;

use yii\filters\AccessControl;

class LogController extends \common\controllers\cpanel\server\LogController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list',
                ],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    

}