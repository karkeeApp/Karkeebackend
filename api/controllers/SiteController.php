<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class SiteController extends \common\controllers\api\SiteController
{
	public function behaviors()
    {
    	return [
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [                    
                    'data-protection-terms' => ['get'],
                    'p9club-terms' => ['get'],
                    'fb-whatsapp-gc-rules' => ['get'],
	            ],
    		],
            'authenticator' => [
                'except' => ['data-protection-terms', 'p9club-terms','fb-whatsapp-gc-rules'],
                'class'  => \yii\filters\auth\QueryParamAuth::class,            
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['data-protection-terms', 'p9club-terms','fb-whatsapp-gc-rules'],
                'formats' => [
                    'text/html' => yii\web\Response::FORMAT_HTML
                ]
            ],
        ];
    }
}