<?php
namespace frontend\controllers;

use yii\filters\AccessControl;

class LeaveController extends \common\controllers\LeaveController
{
    public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'apply'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $data['menu'] = $this->menu;

        return $this->actionList();
    }
}
