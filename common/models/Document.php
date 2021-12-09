<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class Document extends ActiveRecord{    

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    const TYPE_OTHERS = 0;
    const TYPE_PROFILE = 1;
    const TYPE_NRIC = 2;
    const TYPE_INSURANCE = 3;
    const TYPE_AUTHORIZATION = 4;
    const TYPE_LOG_CARD = 5;
    const TYPE_VENDOR = 6;
    const TYPE_TRANSFER_SCREENSHOT = 7;
    const TYPE_ACRA = 8;
    const TYPE_MEMORANDUM = 9;
    const TYPE_CAR_FRONT = 10;
    const TYPE_CAR_BACK = 11;
    const TYPE_CAR_LEFT = 12;
    const TYPE_CAR_RIGHT = 13;
    const TYPE_COMPANY_LOGO = 14;
    const TYPE_CLUB_LOGO = 15;
    const TYPE_BRAND_GUIDE = 16;
    const TYPE_RENEWAL = 17;
    const TYPE_PAYMENT = 18;
    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['doc_id'];
    }

    public static function tableName()
    {
        return '{{%document}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public static function Create($form, $user_id, $type = self::TYPE_OTHERS){
        $document = new self;
        $document->filename     = $form->filename;
        $document->account_id   = $form->account_id;
        $document->user_id      = $user_id;
        $document->status       = self::STATUS_ACTIVE;
        $document->type         = $type;
        $document->save();
    }

    public function data(){
        $data = [];

        $eqfield = self::EquivalentFields()[$this->type];
        $data['account_id'] = $this->account_id;
        $data['user_id'] = $this->user_id;
        $data['type'] = $this->type;
        $data['type_name'] = $this->type();
        $data['field'] = $eqfield;
        $data['mime_type'] = $this->getMimeType();
        $data['link_doc'] = $this->docLink($eqfield);

        return $data;
    }

    public function docLink($attr)
    {
        return ($this->filename)? Url::home(TRUE) . 'file/doc?u=' . $this->doc_id : '';
    }

    private function getMimeType(){
        $path = Yii::$app->params['dir_member'];
        $filepath = $path . $this->filename;
        if(!file_exists($filepath)) return NULL;
        $file_mime = FileHelper::getMimeType($filepath);

        return $file_mime;
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

    public function type()
    {
        return self::types()[$this->type];
    }

    public static function types()
    {
        return [
            self::TYPE_OTHERS               => "Others",
            self::TYPE_PROFILE              => "Profile",
            self::TYPE_NRIC                 => "NRIC",
            self::TYPE_INSURANCE            => "Insurance",
            self::TYPE_AUTHORIZATION        => "Authorization",
            self::TYPE_LOG_CARD             => "Log Card",
            self::TYPE_VENDOR               => "Vendor",
            self::TYPE_TRANSFER_SCREENSHOT  => "Transfer Screenshot",
            self::TYPE_ACRA                 => "ACRA",
            self::TYPE_MEMORANDUM           => "Memorandum",
            self::TYPE_CAR_FRONT            => "Car Front",
            self::TYPE_CAR_BACK             => "Car Back",
            self::TYPE_CAR_LEFT             => "Car Left",
            self::TYPE_CAR_RIGHT            => "Car Right",
            self::TYPE_COMPANY_LOGO         => "Company Logo",
            self::TYPE_CLUB_LOGO            => "Club Logo",
            self::TYPE_BRAND_GUIDE          => "Brand Guide",
            self::TYPE_RENEWAL              => "Renewal",
            self::TYPE_PAYMENT              => "Payment"
        ];
    }

    public static function EquivalentTypes()
    {
        return [
            "others"                        =>  self::TYPE_OTHERS,
            "img_profile"                   =>  self::TYPE_PROFILE,
            "img_nric"                      =>  self::TYPE_NRIC,
            "img_insurance"                 =>  self::TYPE_INSURANCE,
            "img_authorization"             =>  self::TYPE_AUTHORIZATION,
            "img_log_card"                  =>  self::TYPE_LOG_CARD,
            "img_vendor"                    =>  self::TYPE_VENDOR,
            "transfer_screenshot"           =>  self::TYPE_TRANSFER_SCREENSHOT,
            "img_acra"                      =>  self::TYPE_ACRA,
            "img_memorandum"                =>  self::TYPE_MEMORANDUM,
            "img_car_front"                 =>  self::TYPE_CAR_FRONT,
            "img_car_back"                  =>  self::TYPE_CAR_BACK,
            "img_car_left"                  =>  self::TYPE_CAR_LEFT,
            "img_car_right"                 =>  self::TYPE_CAR_RIGHT,
            "company_logo"                  =>  self::TYPE_COMPANY_LOGO,
            "club_logo"                     =>  self::TYPE_CLUB_LOGO,
            "brand_guide"                   =>  self::TYPE_BRAND_GUIDE,
            "renewal"                       =>  self::TYPE_RENEWAL,
            "payment"                       =>  self::TYPE_PAYMENT
        ];
    }

    public static function EquivalentFields()
    {
        return [
            self::TYPE_OTHERS               => "others",
            self::TYPE_PROFILE              => "img_profile",
            self::TYPE_NRIC                 => "img_nric",
            self::TYPE_INSURANCE            => "img_insurance",
            self::TYPE_AUTHORIZATION        => "img_authorization",
            self::TYPE_LOG_CARD             => "img_log_card",
            self::TYPE_VENDOR               => "img_vendor",
            self::TYPE_TRANSFER_SCREENSHOT  => "transfer_screenshot",
            self::TYPE_ACRA                 => "img_acra",
            self::TYPE_MEMORANDUM           => "img_memorandum",
            self::TYPE_CAR_FRONT            => "img_car_front",
            self::TYPE_CAR_BACK             => "img_car_back",
            self::TYPE_CAR_LEFT             => "img_car_left",
            self::TYPE_CAR_RIGHT            => "img_car_right",
            self::TYPE_COMPANY_LOGO         => "company_logo",
            self::TYPE_CLUB_LOGO            => "club_logo",
            self::TYPE_BRAND_GUIDE          => "brand_guide",
            self::TYPE_RENEWAL              => "renewal",
            self::TYPE_PAYMENT              => "payment"
        ];
    }
}