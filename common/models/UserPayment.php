<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;
use common\behaviors\TimestampBehavior;

class UserPayment extends ActiveRecord
{    
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;
    const STATUS_CONFIRMED = 4;
    const STATUS_DELETED = 0;

    const PAYMENT_FOR_PREMIUM = 1;
    const PAYMENT_FOR_RENEWAL = 2;
    const PAYMENT_FOR_ADS = 3;
    const PAYMENT_FOR_EVENT = 4;
    const PAYMENT_FOR_OTHERS = 0;

    public static function tableName()
    {
        return '{{%user_payment}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        return parent::update($runValidation, $attributes);
    }

    public static function Add($form, $user_id)
    {
        $payment                  = new self;
        // $payment->id              = $form->id;
        $payment->account_id      = $form->account_id;
        $payment->ads_id          = $form->ads_id;
        $payment->user_id         = $user_id;
        $payment->amount          = $form->amount;
        $payment->description     = $form->description;
        $payment->filename        = $form->filename;
        $payment->log_card        = $form->log_card;
        $payment->name            = $form->name;
        $payment->payment_for     = $form->payment_for;

        $payment->save();

        return $payment;
    }
    public static function create(\common\forms\UserPaymentForm $form, $user_id)
    {
        $payment                  = new self;
        $payment->account_id      = $form->account_id;
        $payment->user_id         = $user_id;
        $payment->amount          = $form->amount;
        $payment->description     = $form->description;
        $payment->filename        = $form->filename;
        $payment->log_card        = $form->log_card;
        $payment->name            = $form->name;
        $payment->payment_for     = $form->payment_for;

        $payment->save();

        return $payment;
    }

    public function data($user = NULL)
    {        
        $data = $this->attributes;
        $data['renewal_image'] = ($this->renewal ? $this->renewal->docLink() : null);
        $data['renewal_mime_type'] = ($this->renewal ? $this->renewal->screenshot_mime_type : null);

        $data['email'] = $this->user ? $this->user->email : "";
        $data['user'] = $this->user->simpleData();
        $data['amount'] = ($this->amount ? $this->amount : 0);

        $data['payment_for'] = ($this->payment_for ? $this->payment_for : self::PAYMENT_FOR_OTHERS);
                             
        $data['screenshot'] = ($this->filename ? $this->screenshot() : ($this->renewal ? $this->renewal->docLink() : ($this->attendee ? $this->attendee->filelink() : null)) );
        $data['screenshot_mime_type'] = ($this->filename ? $this->screenshot_mime_type : ($this->renewal ? $this->renewal->screenshot_mime_type : ($this->attendee ? $this->attendee->screenshot_mime_type :null)));

        $data['log_card'] = (($this->log_card AND file_exists(Yii::$app->params['dir_payment'].$this->log_card)) ? $this->log_card() : ($this->renewal ? $this->renewal->log_card() : null));
        $data['log_card_mime_type'] = ($this->log_card ? $this->log_card_mime_type : ($this->renewal ? $this->renewal->log_card_mime_type : null));
        
        unset(
            $data['user_id'], 
            $data['account_id'], 
            $data['filename'],
            $data['updated_at']            
        );

        return $data;
    }
    public function payment_attachments($user_id = NULL){
        $pay_attachs = $this->getAttachments();
        if($user_id){
            $pay_attachs = $pay_attachs->where(['user_id' => $user_id]);
        }

        $data = $pay_attachs->all();

        unset($data['amount']);
        unset($data['account_id']);
        unset($data['user_id']);
        unset($data['payment_id']);

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
            self::STATUS_PENDING       => "Pending",
            self::STATUS_APPROVED      => "Approved",
            self::STATUS_REJECTED      => "Rejected",
            self::STATUS_CONFIRMED     => "Confirmed"
        ];
    }

    public function paymentFor()
    {
        return self::paymentTo()[$this->payment_for];
    }
    public static function paymentTo()
    {
        return [
            self::PAYMENT_FOR_OTHERS   => "Others",
            self::PAYMENT_FOR_PREMIUM  => "Premium",
            self::PAYMENT_FOR_RENEWAL  => "Renewal",
            self::PAYMENT_FOR_ADS      => "Ads",
            self::PAYMENT_FOR_EVENT    => "Event"
        ];
    }

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }
    public function isConfirmed()
    {
        return $this->status == self::STATUS_CONFIRMED;
    }
    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }
    public function getIs_image_payment()
    {
        $dir = Yii::$app->params['dir_renewal'];
        $dirpay = Yii::$app->params['dir_payment'];
        $filename = $this->filename;
        if(file_exists($dir . $filename)){

            $mimeType = mime_content_type($dir . $filename);
            if(preg_match("/image/", $mimeType)){
                return true;
            }
        } else if(file_exists($dirpay . $filename)){

            $mimeType = mime_content_type($dirpay . $filename);
            if(preg_match("/image/", $mimeType)){
                return true;
            }
        }
        return false;
    }

    public function getScreenshot_mime_type(){
        if (!empty($this->filename)) {
            $file = Yii::$app->params['dir_renewal'] . $this->filename;
            $filepay = Yii::$app->params['dir_payment'] . $this->filename;

            if (file_exists($file)) return mime_content_type($file); 
            else if (file_exists($filepay)) return mime_content_type($filepay); 
        } 
        return "";
    }

    public function getLog_card_mime_type(){
        if (!empty($this->filename)) {
            $file = Yii::$app->params['dir_renewal'] . $this->log_card;
            $filepay = Yii::$app->params['dir_payment'] . $this->log_card;

            if (file_exists($file)) return mime_content_type($file); 
            else if (file_exists($filepay)) return mime_content_type($filepay); 
        } 
        return "";
    }

    public function screenshot()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return !empty($this->filename)? Url::home(TRUE) . 'file/payment?id=' . $this->id . '&f=' . $this->filename : ''; //'&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return !empty($this->filename)? Url::home(TRUE) . 'file/payment?id=' . $this->id . '&f=' . $this->filename : '';
        }

        return !empty($this->filename)? Url::home(TRUE) . 'file/payment/' . $this->id . '&f=' . $this->filename : NULL; //. '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }

    public function log_card()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return !empty($this->log_card)? Url::home(TRUE) . 'file/log-card?id=' . $this->id . '&f=' . $this->log_card : ''; //'&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return !empty($this->log_card)? Url::home(TRUE) . 'file/log-card?id=' . $this->id . '&f=' . $this->log_card : '';
        }

        return !empty($this->log_card)? Url::home(TRUE) . 'file/log-card/' . $this->id . '&f=' . $this->log_card : NULL; //. '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function getRenewal()
    {
        return $this->hasOne(Renewal::class,['id' => 'renewal_id']);
    }

    public function getEvent()
    {
        return $this->hasOne(Event::class,['id' => 'event_id']);
    }

    public function getAttendee()
    {
        return $this->hasOne(EventAttendee::class,['event_id' => 'event_id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(UserPaymentAttachment::class,['payment_id' => 'id']);
    }
}