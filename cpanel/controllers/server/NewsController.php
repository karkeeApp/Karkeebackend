<?php
namespace cpanel\controllers\server;

use Yii;
use yii\filters\AccessControl;

class NewsController extends \common\controllers\cpanel\server\NewsController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'update', 'mediaupload', 'medialist', 'delete','delete-media', 'gallery-upload', 'gallery-upload-by-token', 'gallery-delete'
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