<?php
namespace cpanel\controllers;

use Yii;
use yii\filters\AccessControl;

class FileController extends \common\controllers\cpanel\FileController
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