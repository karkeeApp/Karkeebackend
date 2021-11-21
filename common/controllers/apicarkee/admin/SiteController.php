<?php
namespace common\controllers\apicarkee\admin;

use common\forms\SettingsForm;
use Yii;
use common\models\Account;
use common\helpers\Common;
use common\lib\Helper;
use common\models\AccountMembership;
use common\models\Ads;
use common\models\BannerImage;
use common\models\Event;
use common\models\EventAttendee;
use common\models\Listing;
use common\models\News;
use common\models\Settings;
use common\models\User;
use common\models\UserPayment;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

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
        return $this->render('fb_whatsapp_gc_rules_b.tpl');
    }

    public function actionSettings(){
        $user = new User;
        $user_roles = [];
        $allRoles = Yii::$app->request->get('all_roles',0);
        if($allRoles AND $allRoles > 0){
            foreach(User::roles() as $key => $role){
                $user_roles[] = ['id' => $key, 'name' => $role ];
            }
        }else{
            // $user_roles[] = ['id' => User::ROLE_USER, 'name' => User::roles()[User::ROLE_USER] ];
            $user_roles[] = ['id' => User::ROLE_SUPERADMIN, 'name' => User::roles()[User::ROLE_SUPERADMIN] ];
            $user_roles[] = ['id' => User::ROLE_ADMIN, 'name' => User::roles()[User::ROLE_ADMIN] ];
            $user_roles[] = ['id' => User::ROLE_SUB_ADMIN, 'name' => User::roles()[User::ROLE_SUB_ADMIN] ];
        }
        $user_status = [];
        foreach(User::statuses() as $key => $status){
            $user_status[] = ['id' => $key, 'name' => $status ];
        }
        $sponsor_levels = [];
        foreach($user->levels() as $key => $level){
            $sponsor_levels[] = ['id' => $key, 'name' => $level ];
        }
        $member_types = [];
        foreach(User::memberTypes() as $key => $type){
            $member_types[] = ['id' => $key, 'name' => $type ];
        }
        
        $club_status = [];
        foreach(Account::statuses() as $key => $status){
            $club_status[] = ['id' => $key, 'name' => $status ];
        }
        
        $ads_status = [];
        foreach(Ads::statuses() as $key => $status){
            $ads_status[] = ['id' => $key, 'name' => $status ];
        }

        $ads_states = [];
        foreach(Ads::states() as $key => $status){
            $ads_states[] = ['id' => $key, 'name' => $status ];
        }

        $banner_status = [];
        foreach(BannerImage::statuses() as $key => $status){
            $banner_status[] = ['id' => $key, 'name' => $status ];
        }

        $news_status = [];
        foreach(News::statuses() as $key => $status){
            $news_status[] = ['id' => $key, 'name' => $status ];
        }

        $news_categories = [];
        foreach(News::categories() as $key => $status){
            $news_categories[] = ['id' => $key, 'name' => $status ];
        }

        $event_status = [];
        foreach(Event::statuses() as $key => $status){
            $event_status[] = ['id' => $key, 'name' => $status ];
        }

        $attendee_status = [];
        foreach(EventAttendee::statuses() as $key => $status){
            $attendee_status[] = ['id' => $key, 'name' => $status ];
        }

        $listing_status = [];
        foreach(Listing::statuses() as $key => $status){
            $listing_status[] = ['id' => $key, 'name' => $status ];
        }

        $premium_status = [];
        foreach(User::premium_statuses() as $key => $status){
            $premium_status[] = ['id' => $key, 'name' => $status ];
        }
        $relationships = [];
        foreach(User::relationships() as $key => $status){
            $relationships[] = ['id' => $key, 'name' => $status ];
        }
        $account_membership_status = [];
        foreach(AccountMembership::statuses() as $key => $status){
            $account_membership_status[] = ['id' => $key, 'name' => $status ];
        }
        $payment_for = [];
        foreach(UserPayment::paymentTo() as $key => $status){
            $payment_for[] = ['id' => $key, 'name' => $status ];
        }        
        $payment_status = [];
        foreach(UserPayment::statuses() as $key => $status){
            $payment_status[] = ['id' => $key, 'name' => $status ];
        }

        return [
            'user_roles' => $user_roles,
            'user_status' => $user_status,
            'sponsor_levels' => $sponsor_levels,
            'member_types' => $member_types,
            'club_status' => $club_status,
            'ads_status' => $ads_status,
            'ads_states' => $ads_states,
            'banner_status' => $banner_status,
            'news_status' => $news_status,
            'news_categories' => $news_categories,
            'event_status' => $event_status,
            'attendee_status' => $attendee_status,
            'listing_status' => $listing_status,
            'premium_status' => $premium_status,
            'account_membership_status' => $account_membership_status,
            'relationships' => $relationships,
            'payment_for' => $payment_for,
            'payment_status' => $payment_status
        ];
    }


    public function actionUpdateDefaultSettings()
    {
        $admin = Yii::$app->user->identity;

        $form = new SettingsForm;
        $form = $this->postLoad($form);
        $form->club_code = $form->club_code ? $form->club_code : mt_rand(100000, 999999);
        
        // $account = Account::findOne($form->account_id);
        // if (!$account ) return Helper::errorMessage('Account not found');
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        if(!$admin->isAdministrator()) return Helper::errorMessage("Can't Update Default Settings! You don't have the required permission to apply changes.",true);
        
        $setting = Settings::findOne(['status'=>Settings::STATUS_ACTIVE]);//->where(['account_id'=>$form->account_id])->one();
        $setting->member_expiry     = $form->member_expiry;
        $setting->enable_ads        = $form->enable_ads;
        $setting->is_one_approval   = $form->is_one_approval;
        $setting->renewal_alert     = $form->renewal_alert;
        $setting->skip_approval     = $form->skip_approval;
        $setting->club_code         = $form->club_code;
        $setting->days_unverified_reg= $form->days_unverified_reg;
        $setting->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully Updated Default Settings.',
        ];
    }
}