<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;
use yii\helpers\Url;

class Settings extends ActiveRecord{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%settings}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['account_id' => 'account_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
    public static function get()
    {
        $settings = self::find()->one();

        if (!$settings) {
            $settings = new self;
            $settings->save();
        }

        return $settings;
    }    
    public static function create(\common\forms\SettingsForm $form, $user = NULL)
    {
        $settings                  = new self;
        $settings->account_id      = $user ? $user->account_id : 0;
        $settings->user_id         = $user ? $user->user_id : NULL;
        $settings->default_interest= $form->default_interest;
        $settings->content         = $form->content;
        $settings->renewal_fee     = $form->renewal_fee;

        $settings->save();
    }

    public function data($user = NULL)
    {
        $data = $this->attributes;
        $data['logo_url'] = $this->logoUrl();
        // $data['user'] = ($this->user ? $this->user->simpleData() : null);
        return $data;
    }
    public function logoUrl()
    {
        return Url::home(TRUE) . 'member/logo?account_id=0&t=' . $this->logo;
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

    public static function renewalFee(){
        return self::find()->one();
    }
}