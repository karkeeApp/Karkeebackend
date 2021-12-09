<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use common\helpers\Common;

class Renewal extends ActiveRecord{    
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%renewal}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->status = self::STATUS_PENDING;
        $this->created_at = date('Y-m-d H:i:s');
        
        return parent::insert($runValidation, $attributes);
    }

    public static function Create($userpayment, $user_id)
    {
        $renewal                  = new self;
        $renewal->account_id      = $userpayment->account_id;
        $renewal->user_id         = $user_id;
        $renewal->paid            = $userpayment->amount;
        $renewal->filename        = $userpayment->filename;
        // $renewal->updated_by      = $user_id;

        $renewal->save();

        return $renewal;
    }
    public function data()
    {
        $data = $this->attributes;
        $data['link'] = $this->docLink();
        $data['log_card'] = $this->log_card();
        $data['screenshot'] = $this->docLink();
        $data['screenshot_mime_type'] = $this->screenshot_mime_type;
        $data['log_card_mime_type'] = $this->log_card_mime_type;
        $data['is_image_logcard'] = $this->is_image_log_card;
        $data['is_image_payment'] = $this->is_image_payment;
        $data['is_image_logcard'] = $this->is_image_log_card;
        $data['email'] = $this->user ? $this->user->email : "";
        $data['amount'] = $this->paid;
        $data['payment_for'] = UserPayment::PAYMENT_FOR_RENEWAL;
        $data['user'] = $this->user;
        return $data;
    }

    public static function statuses()
    {
        return [
            self ::STATUS_PENDING => 'Pending',
            self ::STATUS_APPROVED => 'Approved',
            self ::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function status()
    {
        $statuses = self::statuses();

        return (isset($statuses[$this->status])) ? $statuses[$this->status] : null;
    }
    public function getExpiry()
    {
        return $this->hasOne(MemberExpiry::class,['id' => 'expiry_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class,['account_id' => 'account_id']);
    }
    public function getUser_payment()
    {
        return $this->hasOne(UserPayment::class,['renewal_id' => 'id']);
    }

    public function isPending() { return $this->status == self::STATUS_PENDING; }
    public function isApproved() { return $this->status == self::STATUS_APPROVED; }
    public function isRejected() { return $this->status == self::STATUS_REJECTED; }

    public function docLink()
    {
       // if (empty($this->filename)) $this->filename = 'default-profile.png';

        if (Common::isApi() OR Common::isCarkeeApi()) {
            // return ($this->filename)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->filename . '&u=' . $this->id . '&f=filename&access-token=' . Yii::$app->request->get('access-token') : '';
            return !empty($this->filename)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->filename . '&u=' . $this->id . '&f=filename' : '';
        } elseif(Common::isAccount() OR Common::isCpanel()){
            return !empty($this->filename)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->filename . '&u=' . $this->id . '&f=filename' : '';
        }

        return !empty($this->filename)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->filename . '&u=' . $this->id . '&f=filename' : NULL;

    }    
    

    public function log_card()
    {
        //if (empty($this->log_card)) $this->log_card = 'default-profile.png';


        if (Common::isApi() OR Common::isCarkeeApi()) {
            return !empty($this->log_card)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->log_card . '&u=' . $this->id . '&f=log_card' : '';
        } elseif(Common::isAccount() OR Common::isCpanel()){
            return !empty($this->log_card)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->log_card . '&u=' . $this->id . '&f=log_card' : '';
        }

        return !empty($this->log_card)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->log_card . '&u=' . $this->id . '&f=log_card' : NULL;

    }

    public function isImage()
    {
        $dir = Yii::$app->params['dir_renewal'];
        $filename = $this->filename;
        if(file_exists($dir . $filename)){

            $mimeType = mime_content_type($dir . $filename);
            if(preg_match("/image/", $mimeType)){
                return true;
            }
        }
        return false;
    }

    public function getIs_image_payment()
    {
        $dir = Yii::$app->params['dir_renewal'];
        $filename = $this->filename;
        if(file_exists($dir . $filename)){

            $mimeType = mime_content_type($dir . $filename);
            if(preg_match("/image/", $mimeType)){
                return true;
            }
        }
        return false;
    }

    public function getScreenshot_mime_type(){
        if (!empty($this->filename)) {
            $file = Yii::$app->params['dir_renewal'] . $this->filename;

            if (file_exists($file)) {
                return mime_content_type($file); 
            }
        } 
        return "";
    }

    public function getIs_image_log_card()
    {
        $dir = Yii::$app->params['dir_renewal'];
        $filename = $this->log_card;
        if(file_exists($dir . $filename)){

            $mimeType = mime_content_type($dir . $filename);
            if(preg_match("/image/", $mimeType)){
                return true;
            }
        }
        return false;
    }
    public function getLog_card_mime_type(){
        if (!empty($this->log_card)) {
            $file = Yii::$app->params['dir_renewal'] . $this->log_card;

            if (file_exists($file)) {
                return mime_content_type($file); 
            }
        } 
        return "";
    }
}