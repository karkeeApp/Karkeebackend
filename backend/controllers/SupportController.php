<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 24/04/2021
 * Time: 3:46 PM
 */

namespace backend\controllers;


use yii\filters\AccessControl;

class SupportController extends \common\controllers\SupportController
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