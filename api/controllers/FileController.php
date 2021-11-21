<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;

class FileController extends \common\controllers\FileController
{
    public function behaviors()
    {
        return [
            // 'access' => [
            //     'class' => AccessControl::class,
            //     'rules' => [
            //         [
            //             'actions' => ['identity', 'media', 'mediathumb', 'staffimport', 'download'],
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
        ];
    }
}