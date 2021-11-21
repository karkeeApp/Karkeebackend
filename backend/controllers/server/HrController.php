<?php
namespace backend\controllers\server;

use Yii;
use yii\filters\AccessControl;

class HrController extends \common\controllers\server\HrController
{
    public function behaviors()
    {
        $accountAdmin = Yii::$app->user->getIdentity();
        
        $actions = ['dummy'];

        if ($accountAdmin->isAdministrator()){
            $actions = array_merge($actions, ['list', 'updatepassword', 'update']);
        }

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => $actions,
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
}
