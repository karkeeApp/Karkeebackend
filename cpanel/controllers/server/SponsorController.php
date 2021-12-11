<?php
namespace cpanel\controllers\server;

use Yii;
use yii\filters\AccessControl;

class SponsorController extends \common\controllers\cpanel\server\SponsorController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list','create', 'update', 'delete','add-sponsor', 'edit-sponsor', 'silver','gold','platinum',
                    'diamond','remove-level','normal'
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