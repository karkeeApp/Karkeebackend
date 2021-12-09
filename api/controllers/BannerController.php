<?php
namespace api\controllers;

use Yii;

class BannerController extends \common\controllers\api\BannerController
{
	public function behaviors()
    {
    	return [
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [
                    'list' => ['get'],
	            ],
    		],
            'authenticator' => [
                'except' => ['list'],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['list'],
                'formats' => [
                    'text/html' => yii\web\Response::FORMAT_JSON
                ]
            ],
            
        ];
    }


}