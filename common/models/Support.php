<?php
namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\helpers\Common;
use common\plugins\sendgrid\SendGridEmail;

class Support extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public static function tableName()
    {
        return '{{%support}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    public function attributeLabels()
    {
        return [
            'description' => 'Messages/Inquiries'
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

    public function getSupport_reply()
    {
        return $this->hasOne(SupportReply::class, ['support_id' => 'id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class, ['account_id' => 'account_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public static function create(\common\forms\SupportForm $form, $user = NULL)
    {
        $support                  = new self;
        $support->account_id      = $user ? $user->account_id : 0;
        $support->user_id         = $user ? $user->user_id : NULL;
        $support->title           = $form->title;
        $support->description     = $form->message;

        $support->save();

        if($user){
                $club_name = $user->account_id > 0 ? strtoupper($user->account->company) : "KARKEE";

                Email::sendSendGridAPI(Yii::$app->params['admin.email'], $club_name.' - Inquire/Support', ($user->account_id > 0 ? 'club-inquiry' : 'carkee-inquiry'), $params=[
                    'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'message'    => $form->message,
                    'address'    => !empty($user->add_1) ? $user->add_1 : (!empty($user->company) ? $user->company : "Singapore"),
                    'email'      => $user->email,
                    'client_email'  => $user->email,
                    'club_email'    => $user->account_id > 0 ? $user->account->email : "admin@carkee.sg",
                    'club_name'     => $club_name,
                    'club_link'     => $user->account_id > 0 ? $user->account->club_link : "http://cpanel.carkee.sg",
                    'club_logo'     => $user->account_id > 0 ? $user->account->logo_link : "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ], null, true);
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

    public function getEmail(){
        return $this->user ? $this->user->email : " - ";
    }
 
    public function getName(){
        return $this->user ? ($this->user->fullname ? $this->user->fullname : $this->user->firstname) : " - ";
    }
}