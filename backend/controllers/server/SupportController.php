<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 24/04/2021
 * Time: 3:46 PM
 */

namespace backend\controllers\server;


use yii\filters\AccessControl;

class SupportController extends \common\controllers\server\SupportController
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