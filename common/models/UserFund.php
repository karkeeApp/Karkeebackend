<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserFund extends ActiveRecord{    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['fund_id'];
    }

    public static function tableName()
    {
        return '{{%user_fund}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function creditBalance()
    {
        return $this->creditLimit() - $this->creditUsed();
    }

    public function creditLimit()
    {
        return (float)$this->credit_limit;
    }

    public function creditUsed()
    {
        return (float)$this->credit_used;
    }

}