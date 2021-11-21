<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;
use common\behaviors\TimestampBehavior;

class UserPaymentAttachment extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public static function tableName()
    {
        return '{{%user_payment_attachment}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function create(\common\forms\UserPaymentAttachmentForm $form, $user_id)
    {
        $payment                  = new self;
        $payment->account_id      = $form->account_id;
        $payment->user_id         = $user_id;
        $payment->name            = $form->name;
        $payment->payment_id      = $form->payment_id;
        $payment->description     = $form->description;
        $payment->filename        = $form->filename;
        
        $payment->save();

        return $payment;
    }

    public function data($user = NULL)
    {        
        $data = $this->attributes;
        $data['link'] = $this->filelink();

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

    public function filelink()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->filename)? Url::home(TRUE) . 'file/payment-attachments?id=' . $this->payment_id : ''; // '&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->filename)? Url::home(TRUE) . 'file/payment-attachments?id=' . $this->payment_id : '';
        }

        return ($this->filename)? Url::home(TRUE) . 'file/payment-attachments/' . $this->payment_id . '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
    }

    public function getSponsors()
    {
        return $this->hasMany(Sponsor::class,['id' => 'sponsor_id']);
    }
}