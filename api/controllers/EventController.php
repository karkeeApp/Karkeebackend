<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class EventController extends \common\controllers\api\EventController
{
	public function behaviors()
    {
    	return [
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [
                    'ongoing'      => ['get'],
                    'past'         => ['get'],
                    'view'         => ['get'],
                    'view-private' => ['get'],
                    'gallery'      => ['get'],
                    'join'         => ['post'],
	            ],
    		],
            'authenticator' => [
                'except' => ['view-private'],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['view', 'view-private'],
                'formats' => [
                    'text/html' => yii\web\Response::FORMAT_HTML
                ]
            ],
        ];
    }


}