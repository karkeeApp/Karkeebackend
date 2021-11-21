<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Page extends ActiveRecord{    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%page}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->created_admin_id = Yii::$app->user->getId();
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_admin_id = Yii::$app->user->getId();
        return parent::update($runValidation, $attributes);
    }

    
    public static function getByName($pageName='')
    {
        $page = self::find()->where(['name' => $pageName])->one();

        if (!$page) $page = new self;

        return $page;        
    }

    public static function getContent($pageName='')
    {
        $page = self::find()->where(['name' => $pageName])->one();

        return ($page)? $page->content : '';
    }
}