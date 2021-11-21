<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;

class MemberExpiry extends ActiveRecord{    
    const STATUS_PAID = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    

    public static function tableName()
    {
        return '{{%member_expiry}}';
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
     
    public function getRenewal()
    {
        return $this->hasOne(Renewal::class, ['id' => 'renewal_id']);
    }
    // public static function create(\common\forms\SettingsForm $form, $user = NULL)
    // {
    //     $memexpiry                  = new self;
    //     $memexpiry->account_id      = $user ? $user->account_id : 0;
    //     $memexpiry->user_id         = $user ? $user->user_id : NULL;

    //     $memexpiry->save();
    // }

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
            self::STATUS_DELETED       => 'Deleted',
            self::STATUS_PAID          => 'Paid'
        ];
    }

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }
}