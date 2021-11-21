<?php
namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\helpers\Common;


class Ads extends ActiveRecord
{    
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const ADS_ON = 1;
    const ADS_OFF = 0;

    const ADS_IS_BOTTOM = 1;
    const ADS_ISNOT_BOTTOM = 0;

    public static function tableName()
    {
        return '{{%ads}}';
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

    public static function create(\common\forms\AdsForm $form, $user_id)
    {
        $ads                  = new self;
        $ads->account_id      = $form->account_id;
        $ads->user_id         = $user_id;
        $ads->name            = $form->name;
        $ads->image           = $form->filename;
        $ads->description     = $form->description;
        $ads->link            = $form->link;
        $ads->is_bottom       = $form->is_bottom;
        $ads->status          = self::STATUS_ACTIVE;

        $ads->save();
        
        return $ads;
    }

    public function data($user = NULL)
    {
        // $data = $this->attributes;
        $data['ads_id'] = $this->id;
        $data['type'] = 'ads';
        $data['title'] = $this->name;
        $data['created_at'] = $this->created_at;
        $data['summary'] = $this->description;
        $data['image'] = $this->filelink();
        $data['url'] = $this->link;
        $data['file'] = $this->filelink();
        $data['link'] = $this->link;
        $data['enable_ads'] = $this->enable_ads;
        $data['is_bottom'] = $this->is_bottom == self::ADS_IS_BOTTOM ? true : false;
        $data['view_more_message'] = null;
        $data['status'] = $this->status;
        $data['status_name'] = $this->statusName();
        // $data['galleries'] = [];

        // unset($data['id']);
        // unset($data['link']);
        // unset($data['name']);
        // unset($data['account_id']);
        // unset($data['user_id']);
        // unset($data['description']);
        // unset($data['is_bottom']);
        // unset($data['created_at']);
        // unset($data['updated_at']);
        // unset($data['status']);

        // $data['remove_attachment'] = $this->remove_attachments($user);

        return $data;
    }
    public function remove_attachments($user_id = NULL){
        $rem_attachs = $this->getAttachments();
        if($user_id){
            $rem_attachs = $rem_attachs->where(['user_id' => $user_id]);
        }

        $data = $rem_attachs->all();

        unset($data['name']);
        unset($data['account_id']);
        unset($data['user_id']);
        unset($data['ads_id']);

        return $data;
    }
    public function statusName()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DELETED       => 'Delete',
            self::STATUS_ACTIVE        => "Active"
        ];
    }

    public function statesLabel()
    {
        return self::states()[$this->enable_ads];
    }

    public static function states()
    {
        return [
            self::ADS_OFF       => 'Off',
            self::ADS_ON        => "On"
        ];
    }

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }

    public function filelink()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->image)? Url::home(TRUE) . 'file/ads?id=' . $this->id : ''; //. '&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->image)? Url::home(TRUE) . 'file/ads?id=' . $this->id : '';
        }

        return ($this->image)? Url::home(TRUE) . 'file/ads/' . $this->id : ''; // . '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(AdsRemoveAttachment::class,['ads_id' => 'id']);
    }
    public static function BottomRandomList()
    {
        $ads = self::find()
            // ->where(['account_id' => 0])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->andWhere(['is_bottom' => 1])
            ->all();
        
        $randomInd = 0;
        $adIds_arr = [];
        $arr_ids = ArrayHelper::getColumn($ads, 'id');
        $ind = 0;
        $ads_count = count($arr_ids);
        if($ads_count <= 0) return null;

        // $rand_limit = 1;
        // if($ads_count > 1) $rand_limit = count($arr_ids) - 1;
        $randomInd = mt_rand(0,(count($arr_ids) - 1));                                  
            
        
        $adsid = $arr_ids[$randomInd];
        $adsran = self::findOne($adsid);
        
        return $adsran->data();
    }

    public static function NormalRandomList()
    {
        $ads = self::find()
            ->where(['account_id' => 0])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->andWhere(['is_bottom' => 0])
            ->all();
        
        $randomInd = 0;
        $adIds_arr = [];
        $arr_ids = ArrayHelper::getColumn($ads, 'id');
        $ind = 0;
        $ads_count = count($arr_ids);
        if($ads_count <= 0) return null;

        // $rand_limit = 1;
        // if($ads_count > 1) $rand_limit = count($arr_ids) - 1;
        $randomInd = mt_rand(0,(count($arr_ids) - 1));                                  
            
        
        $adsid = $arr_ids[$randomInd];
        $adsran = self::findOne($adsid);
        
        return $adsran->data();
    }

    public static function RandomAllList()
    {
        $ads = self::find()
            ->where(['account_id' => 0])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            // ->andWhere(['is_bottom' => 1])
            ->all();
        
        $randomInd = 0;
        $adIds_arr = [];
        $arr_ids = ArrayHelper::getColumn($ads, 'id');
        $ind = 0;
        $ads_count = count($arr_ids);
        if($ads_count <= 0) return null;

        // $rand_limit = 1;
        // if($ads_count > 1) $rand_limit = count($arr_ids) - 1;
        $randomInd = mt_rand(0,(count($arr_ids) - 1));                                  
            
        
        $adsid = $arr_ids[$randomInd];
        $adsran = self::findOne($adsid);
        
        return $adsran->data();
    }
}