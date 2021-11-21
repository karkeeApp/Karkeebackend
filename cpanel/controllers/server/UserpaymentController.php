<?php
namespace cpanel\controllers\server;

use Yii;
use yii\filters\AccessControl;

class UserpaymentController extends \common\controllers\cpanel\server\UserpaymentController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'update', 'create', 'delete', 'view', 'approve', 'reject'
                ],
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