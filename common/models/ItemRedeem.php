<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ItemRedeem extends ActiveRecord
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED  = 3;

    const REDEEM_INFO_BUYER = 1;
    const REDEEM_INFO_VENDOR = 2;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%item_redeem}}';
    }
    
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::class,
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function getItem()
    {
        return $this->hasOne(Item::class, ['item_id' => 'item_id']);
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        return parent::update($runValidation, $attributes);
    }

    public static function add(Item $item, User $user)
    {
        if ($item->redeemCount >= $item->limit) {
            throw new \yii\web\HttpException(404, Yii::t('app', 'Already reached the limit.'));
        }

        $redeem             = new self;
        $redeem->user_id    = $user->user_id;
        $redeem->account_id = $user->account_id;
        $redeem->item_id    = $item->item_id;
        $redeem->save();

        return $redeem;
    }

    public function data($dataType = NULL)
    {
        $data = $this->attributes;

        if ($dataType == self::REDEEM_INFO_BUYER){
            $data['buyer_info'] = $this->user->buyerData();          
        } elseif($dataType == self::REDEEM_INFO_VENDOR) {
            $data['vendor_info'] = $this->item->user->vendorData();            
        }

        unset($data['account_id']);

        return $data;
    }

    public function created_at($format = 'd/m/Y m:i A')
    {
        return date($format, strtotime($this->created_at));
    }
}