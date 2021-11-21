<?php
namespace backend\controllers\server;

use Yii;
use yii\filters\AccessControl;

class AccountController extends \common\controllers\server\AccountController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['updatesettings', 'updatepassword',],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
}
