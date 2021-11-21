<?php
namespace apicarkee\controllers\club;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;

class MemberController extends \common\controllers\apicarkee\club\MemberController
{
	public function behaviors()
    {
    	return [
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [
                    'list'                         => ['get'],  
                    'info'                         => ['get'],  
	            ],
    		],
            
            'authenticator' => [
	            'except' => [],
                'class' => \yii\filters\auth\QueryParamAuth::class,            
            ]
        ];
    }


}