<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;

class AccountMembership extends ActiveRecord{  
    const STATUS_DELETED = 0;  
    const STATUS_PENDING  = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%account_membership}}';
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
    public function getMember_security_answers()
    {
        return $this->hasMany(MemberSecurityAnswers::class, ['account_membership_id' => 'id']);
    }
     
    public static function Create(\common\forms\AccountMembershipForm $form, $user = NULL)
    {
        $accountmem                  = new self;
        $accountmem->account_id      = $form->account_id ? $form->account_id : 0;
        $accountmem->user_id         = $user->user_id;
        $accountmem->club_code       = $form->club_code;
        $accountmem->filename        = $form->filename;
        $accountmem->description     = $form->description;
        $accountmem->save();

        return $accountmem;
    }

    public function data($user = NULL)
    {
        $data = [];
        $data = $this->attributes;
        $data['account'] = $this->account;
        $data['user'] = $this->user;
        return $data;
    }

    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DELETED       => 'Deleted',
            self::STATUS_PENDING       => 'Pending',
            self::STATUS_APPROVED      => 'Approved',
            self::STATUS_REJECTED      => 'Rejected'
        ];
    }
    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }
    public function isConfirmed()
    {
        return $this->confirmed_by AND $this->confirmed_by > 0;
    }
    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }
}