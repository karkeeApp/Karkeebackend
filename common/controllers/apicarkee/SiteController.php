<?php
namespace common\controllers\apicarkee;

use Yii;
use common\models\Account;
use common\helpers\Common;
use common\models\User;

class SiteController extends Controller
{
    public function actionDataProtectionTerms()
    {
        return $this->render('data_protection_terms.tpl');
    }

    public function actionP9clubTerms()
    {
        return $this->render('p9club_terms.tpl');
    }
    
    public function actionFbWhatsappGcRules()
    {
        return $this->render('policy.tpl');
    }

    public function actionVerifyRegistration(){
        $user_id = Yii::$app->request->get('user_id',null);
        $verification_code = Yii::$app->request->get('verification_code',null);
        $account_id = Yii::$app->request->get('account_id',0);

        $user = User::find()
                    ->where(['user_id' => $user_id])
                    ->andWHere(['account_id' => $account_id])
                    ->one();

        if (!$user){
            return '<H1>Member not found!</H1>';
        }
        if (!$user->user_settings){
            return '<H1>Member Settings not set!</H1>';
        }
        if ($user->user_settings->verification_code != $verification_code AND $user->user_settings->is_verified == 0){
            return '<H1>Invalid Registration Code!</H1>';
        }

        $data['is_verified'] = $user->user_settings->is_verified;
        $data['verification_code'] =$user->user_settings->verification_code;
        $data['email'] = $user->email;
        $data['name'] = $user->fullname;
        $data['user_id'] = $user->user_id;
        $data['account_id'] = $user->account_id;

        $user->user_settings->is_verified = 1;
        $user->user_settings->save();

        return $this->render('verification_successful.tpl',$data);
    }
}