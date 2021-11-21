<?php
namespace backend\controllers;

use Yii;
use yii\web\View;
use yii\filters\AccessControl;

class ItemController extends \common\controllers\ItemController
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
                        'actions' => parent::userActions(),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}