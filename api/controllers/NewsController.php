<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class NewsController extends \common\controllers\api\NewsController
{
	public function behaviors()
    {
    	return [
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [
                    'list'         => ['get'],
                    'trending'     => ['get'],
                    'news'         => ['get'],
                    'happening'    => ['get'],
                    'event'        => ['get'],
                    'guest'        => ['get'],
                    'view'         => ['get'],
                    'view-private' => ['get'],
                    'gallery'      => ['get'],
                    
                    'set-public'   => ['post'],
	            ],
    		],
            'authenticator' => [
                'except' => ['list', 'view', 'gallery', 'guest', 'set-public','view-private'],
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