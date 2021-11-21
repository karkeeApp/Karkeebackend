<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class SupportController extends \common\controllers\api\SupportController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'inquire' => ['post'],
                ],
            ],
            'authenticator' => [
                'except' => [],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }


}