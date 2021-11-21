<?php
namespace cpanel\controllers;


use Yii;
use yii\web\View;
use yii\filters\AccessControl;

class UserpaymentController extends \common\controllers\cpanel\UserpaymentController
{
    public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'media-library'
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}