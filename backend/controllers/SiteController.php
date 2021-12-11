<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\forms\LoginForm;
use backend\forms\ResetPasswordForm;
use common\models\Account;
use common\models\Email;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends \common\controllers\SiteAdminController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'forgot-password','reset-password'],
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
                    // 'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        if ($user->isRoleSuperAdmin()) $this->redirect('member');
        elseif ($user->isRoleAdmin()) $this->redirect('member');
        elseif ($user->isRoleMembership()) $this->redirect('member');
        elseif ($user->isRoleAccount()) $this->redirect('member');
        elseif ($user->isRoleSponsorship()) $this->redirect('vendor');
        elseif ($user->isRoleMarketing()) $this->redirect('news');
        elseif ($user->isRoleEditor()) $this->redirect('news');
        else $this->redirect('member');
    }

    public function actionLogin($company=NULL)
    {
        $this->layout = 'login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $account = Account::findOne(['company' => $company]);

        if (!$account) {
            throw new \yii\web\HttpException(404, 'Page not found.');
        }

        $model = new LoginForm();
        $model->account_id = $account->account_id;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {                       
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $user = Yii::$app->user->identity;
        $prefix = strtolower($user->account->prefix);

        Yii::$app->user->logout();

        return $this->redirect(Url::home() . 'login/' . $prefix);
    }

    public function actionResetPassword($account_id=NULL)
    {
        $this->layout = 'login';

        $account = Account::findOne(['account_id' => $account_id]);
        $company = $account ? $account->company : "";
        $model = new ResetPasswordForm(['scenario' => 'reset-password']);
        $model->account_id = $account_id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user = $model->getUser();
            if($user){
                $user->setPassword($model->password);
                $user->reset_code = NULL;
                $user->save();                

                return $this->redirect(Url::home() . 'login/'.$company);
            }else{
                Yii::$app->session->setFlash('error', "Failed to change password!");

                
            }
        }

        // return $this->goBack();
        
        return $this->render('reset-password', [
            'model' => $model,
            'company' => $company
        ]);
        
    }

    public function actionForgotPassword($account_id=NULL)
    {
        $this->layout = 'login';
               
        $model = new ResetPasswordForm(['scenario' => 'reset-codes']);
        $model->account_id = $account_id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /**
             * Send email notificaton
             */
            $user = $model->getUser();
            if($user){

                // $user->reset_code = (Yii::$app->params['environment'] == 'development') ? 123123 : mt_rand(100000, 999999);
                $user->reset_code = mt_rand(100000, 999999);
                $user->save();

                $club_name = strtoupper($user->account->company);

                // if(Yii::$app->params['environment'] == 'development'){
                    Email::sendSendGridAPI($user->email, $club_name.' - Reset password', 'club-reset-password', $params=[
                        'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                        'reset_code' => $user->reset_code,
                        'client_email'  => $user->email,
                        'club_email'    => $user->account->email,
                        'club_name'     => $club_name,
                        'club_link'     => $user->account->club_link,
                        'club_logo'     => $user->account->logo_link,
                        'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                    ]);
                // }else{
                //     Email::send($user->email, 'Karkee - Reset password', 'carkee-reset-password', $params=[
                //         'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                //         'reset_code' => $user->reset_code
                //     ]);
                // }
                Yii::$app->session->setFlash('success', "Reset Codes successfully sent to your registered email!");

      
                return $this->redirect(Url::home() . 'site/forgot-password?account_id='.$account_id);
                
            }else{
                Yii::$app->session->setFlash('error', "Failed to send reset codes!");
            }
            
        } 
        
        return $this->render('forgot-password', [
            'model' => $model,
        ]);
        
    }
}
