<?php
namespace backend\controllers\server;

use Yii;
use yii\filters\AccessControl;

class AccountadminController extends \common\controllers\server\AccountadminController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['list', 'updatepassword', 'update', 'delete', 'update-role'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => parent::userActions(),
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
}
