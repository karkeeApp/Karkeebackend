<?php
namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\helpers\Common;
use common\plugins\sendgrid\SendGridEmail;

class SupportReply extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public static function tableName()
    {
        return '{{%support_reply}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    // public function insert($runValidation = true, $attributes = NULL)
    // {
    //     return parent::insert($runValidation, $attributes);
    // }

    // public function update($runValidation = true, $attributes = NULL)
    // {
    //     return parent::update($runValidation, $attributes);
    // }

    public function getSupport()
    {
        return $this->hasOne(Support::class, ['id' => 'support_id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class, ['account_id' => 'account_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public static function create(\common\forms\SupportReplyForm $form, $user = NULL)
    {
        $support                  = new self;
        $support->support_id      = $form->support_id;
        $support->account_id      = $user ? $user->account_id : 0;
        $support->user_id         = $user ? $user->user_id : NULL;
        $support->title           = $form->title;
        $support->message         = $form->message;

        $support->save();

        if($user){
            $inquiries = Support::findOne($form->support_id);

            $club_name = $user->account_id > 0 ? strtoupper($user->account->company) : "KARKEE";
            // if(Yii::$app->params['environment'] == 'development'){
                Email::sendSendGridAPI($user->email, $club_name.' - Inquire/Support Reply', ($user->account_id > 0 ? 'club-inquiry-reply' : 'carkee-inquiry-reply'), $params=[
                    'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'inquiry'    => $inquiries->description,
                    'message'    => $form->message,
                    'address'    => !empty($user->add_1) ? $user->add_1 : (!empty($user->company) ? $user->company : "Singapore"),
                    'email'      => Yii::$app->params['admin.email'],
                    'client_email'  => $user->email,
                    'club_email'    => $user->account_id > 0 ? $user->account->email : "admin@carkee.sg",
                    'club_name'     => $club_name,
                    'club_link'     => $user->account_id > 0 ? $user->account->club_link : "http://cpanel.carkee.sg",
                    'club_logo'     => $user->account_id > 0 ? $user->account->logo_link : "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ], null, true);
            // }else{
            //     Email::send(Yii::$app->params['admin.email'], 'Club - Inquire/Support', 'club-inquiry', $params=[
            //         'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
            //         'message'    => $form->message,
            //         'email'      => $user->email,
            //         'club_email' => $user->account->email,
            //         'club_name'  => $user->account->company
            //     ], null, true);
            // }

        }
        return $support;
    }

    public function data($user = NULL)
    {
        $data = $this->attributes;
        
        return $data;
    }
   
    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE        => "Active",
            self::STATUS_DELETED       => 'Deleted'
        ];
    }

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }
 
    public function getInquiry(){
        return $this->support->description;
    }
}