<?php
namespace frontend\controllers\server;

use yii\filters\AccessControl;

class LeaveController extends \common\controllers\server\LeaveController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['apply', 'list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}

