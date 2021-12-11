<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use cpanel\forms\LoginForm;
use cpanel\forms\ResetPasswordForm;

use common\models\Account;
use common\models\User;
use common\models\Watchdog;

use common\helpers\UserHelper;
use common\models\Email;
use yii\helpers\Url;

class SiteController extends \common\controllers\cpanel\SiteAdminController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['api'],
                'rules' => [
                    [
                        'actions' => ['doc','login', 'error', 'updatescores','mobile-dashboard', 'forgot-password','reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'mobile-dashboard' => ['get'],
                    'logout' => ['post'],
                    'updatescores' => ['post', 'get'],                    
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\web\ServerErrorAction',
            ],

            //The document preview addesss:http://api.yourhost.com/site/doc
            'doc' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => \yii\helpers\Url::to(['/site/api'], true),

            ],
            //The resultUrl action.
            'api' => [
                'class' => 'light\swagger\SwaggerApiAction',
                //The scan directories, you should use real path there.
                'scanDir' => [
                    //Yii::getAlias('@swagger'), 
                    Yii::getAlias('@swagger'),
                ],
                //The security key
                'api_key' => null,
            ],
        ];
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {                       
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionResetPassword(){
        $this->layout = 'login';

        $model = new ResetPasswordForm(['scenario' => 'reset-password']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user = $model->getUser();
            if($user){
                $user->setPassword($model->password);
                $user->reset_code = NULL;
                $user->save();                

                return $this->redirect(Url::home() . 'login');
            }else{
                Yii::$app->session->setFlash('error', "Failed to change password!");

                
            }
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
        
    }

    public function actionForgotPassword()
    {
        $this->layout = 'login';
        
        $model = new ResetPasswordForm(['scenario' => 'reset-codes']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /**
             * Send email notificaton
             */
            $user = $model->getUser();
            if($user){

                $user->reset_code =  mt_rand(100000, 999999);
                $user->save();

                // if(Yii::$app->params['environment'] == 'development'){
                    Email::sendSendGridAPI($user->email, 'KARKEE - Reset password', 'carkee-reset-password', $params=[
                        'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                        'reset_code' => $user->reset_code,
                        'client_email'  => $user->email,
                        'club_email'    => "admin@carkee.sg",
                        'club_name'     => "KARKEE",
                        'club_link'     => "http://cpanel.carkee.sg",
                        'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                        'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                    ]);
                // }else{
                //     Email::send($user->email, 'Karkee - Reset password', 'carkee-reset-password', $params=[
                //         'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                //         'reset_code' => $user->reset_code,
                //         'client_email' => $user->email
                //     ]);
                // }
                Yii::$app->session->setFlash('success', "Reset Codes successfully sent to your registered email!");

      
                return $this->redirect(Url::home() . 'site/forgot-password');
                
            }else{
                Yii::$app->session->setFlash('error', "Failed to send reset codes!");
            }
            
        } 
        
        return $this->render('forgot-password', [
            'model' => $model,
        ]);
        
    }

    public function actionIndex()
    {
        $data = [];

        /**
         * Top 10 only
         */
        $data['logs'] = Watchdog::find()
            ->where(['account_id' => 0])
            ->orderBy(['wid' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('index.tpl', $data);
    }

    public function actionMobileDashboard()
    {
        
        $this->layout = 'public';
        
        $user = User::find()->where(['auth_key' => Yii::$app->request->get('access-token', '000')])->one();
       
        if($user){
            
            Yii::$app->user->login($user, (3600 * 24 * 30));

            return $this->goHome(); 
            
        }
        
        // throw new \yii\web\HttpException(404, 'Invalid Credentials.');
        
        return $this->render('error', ['title' => 'Invalid Credentials.', 'message' => 'Invalid Credentials', 'name' => 'Admin Cpanel']);
        
    }

}
