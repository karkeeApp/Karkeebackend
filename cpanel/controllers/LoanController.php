<?php
namespace cpanel\controllers;

use Yii;
use yii\filters\AccessControl;

class LoanController extends \common\controllers\cpanel\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}