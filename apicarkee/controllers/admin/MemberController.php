<?php
namespace apicarkee\controllers\admin;

use apicarkee\forms\AdminLoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

use common\forms\LoanForm;
use common\lib\Helper;
use common\models\UserFcmToken;
use yii\bootstrap\ActiveForm;

class MemberController extends \common\controllers\apicarkee\admin\MemberController
{
	public function behaviors()
    {
    	return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                // 'cors' => [
                //     // restrict access to
                //     'Origin' => ['http://www.myserver.com', 'https://www.myserver.com'],
                //     // Allow only POST and PUT methods
                //     'Access-Control-Request-Method' => ['POST', 'PUT'],
                //     // Allow only headers 'X-Wsse'
                //     'Access-Control-Request-Headers' => ['X-Wsse'],
                //     // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                //     'Access-Control-Allow-Credentials' => true,
                //     // Allow OPTIONS caching
                //     'Access-Control-Max-Age' => 3600,
                //     // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                //     'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                // ],

            ],
    		'verbs' => [
    			'class' => \yii\filters\VerbFilter::class,
	            'actions' => [
                    'login'                        => ['post'],
                    'doc'                          => ['get'],
                    'info'                         => ['get'],                  
                    'info-by-user-id'              => ['get'],                  
                    'options'                      => ['get'],                  
                    'options-by-user-id'           => ['get'],   
                    'upload-doc'                   => ['post'],               
                    'forgot-password'              => ['post'],
                    'forgot-password-confirm-code' => ['post'],
                    'forgot-password-update'       => ['post'],            
                    'social-media-check'           => ['post'],

                    // ===========================

                    'index'                        => ['get'], 
                    'list'                         => ['get'], 
                    'create'                       => ['post'],
                    'create-no-approval'           => ['post'],
                    'update'                       => ['put', 'post'], 
                    'approve'                      => ['post'],
                    'reject'                       => ['post'],
                    'delete'                       => ['delete', 'post'],  
                    'set-sponsor'                  => ['put', 'post'],
                    'sponsor-level'                => ['put', 'post'],
                    'set-expiry'                   => ['put', 'post'], 
                    'change-role'                  => ['post'],
                    'set-default-expiry'           => ['post'],
                    'set-renewal-reminder'         => ['post'],
                    'skip-member-approval'         => ['post'],
                    'set-one-approval'             => ['post'],
                    'update-default-settings'      => ['post'],
                    'file-security-answers'        => ['get'],
                    'file-security-answers'        => ['get'],
                    'list-security-answers-by-user-id' => ['get']
                    
                    // 'attach'                       => ['post'],
                    // 'attach-remove'                => ['post'],
	            ],
    		],

            'authenticator' => [
                'except' => ['login','index','forgot-password', 'forgot-password-confirm-code', 
                             'forgot-password-update','doc','social-media-check','file-security-answers'
                            ],
                'class' => \yii\filters\auth\CompositeAuth::class,
                    'authMethods' => [
                        \yii\filters\auth\HttpBasicAuth::class,
                        \yii\filters\auth\HttpBearerAuth::class,
                        \yii\filters\auth\QueryParamAuth::class,
                    ],          
            ]
        ];
    }

    public function actionLogin()
    {
        $form = new AdminLoginForm();
        $form = $this->postLoad($form);
         
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'],true);
        }

        $user = Yii::$app->user->identity;

        if($user->user_fcm){ 
            $userfcm = UserFcmToken::find()->where(['user_id'=>$user->user_id])->andWhere(['account_id'=>$user->account_id])->one();
        }else{
            $userfcm = new UserFcmToken;
            $userfcm->user_id = $user->user_id; 
            $userfcm->account_id = $user->account_id; 
        }

               
        if($form->fcm_token) $userfcm->fcm_token = $form->fcm_token;
        if(!is_null($form->fcm_topics)) $userfcm->fcm_topics = $form->fcm_topics;

        $userfcm->save();

        // if ($user->isMembershipNearExpire()) Helper::pushNotificationFCM_ToPerMemberNearExpiry($user);
        
        return [
            'code'  => self::CODE_SUCCESS,   
            'token' => $user->auth_key
        ];
    }
}