<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\User;
use common\helpers\HRHelper;

class PayslipController extends \common\controllers\cpanel\PayslipController
{
    public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('@common/views/payslip/menu.tpl');

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->actionList();
    }

}