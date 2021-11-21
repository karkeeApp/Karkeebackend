<?php
namespace frontend\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use common\helpers\Common;
use common\forms\PasswordForm;

class SettingsController extends \common\controllers\server\StaffController
{
    public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['updatepassword', 'updateemail', 'updatemobile'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
}
