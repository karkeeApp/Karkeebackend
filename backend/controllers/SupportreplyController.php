<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 28/04/2021
 * Time: 7:46 AM
 */

namespace backend\controllers;


use yii\filters\AccessControl;

class SupportreplyController extends \common\controllers\SupportreplyController
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