<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\web\View;
use yii\filters\AccessControl;

class ItemController extends \common\controllers\cpanel\ItemController
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
                        'actions' => parent::userActions('auth'),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}