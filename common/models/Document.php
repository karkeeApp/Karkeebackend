<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Document extends ActiveRecord{    
    const TYPE_PROFILE = 1;
    const TYPE_PROFILE = 1;
    const TYPE_PROFILE = 1;
    const TYPE_PROFILE = 1;
    

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

}