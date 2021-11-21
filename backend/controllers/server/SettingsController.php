<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 28/04/2021
 * Time: 7:47 AM
 */

namespace backend\controllers\server;


use yii\filters\AccessControl;

class SettingsController extends \common\controllers\server\SettingsController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'update', 'delete', 'create'
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
                    ],
                ],
            ],
        ];
    }
}