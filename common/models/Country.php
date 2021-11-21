<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Country extends ActiveRecord{    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['country_code'];
    }

    public static function tableName()
    {
        return '{{%country}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        return parent::insert($runValidation, $attributes);
    }

    public static function all()
    {
        $countries = self::find()->orderBy(['nicename' => SORT_ASC])->all();

        $result = [];

        if ($countries) {
            foreach($countries as $country)
            $result[$country->nicename] = $country->nicename;
        }

        return $result;
    }

    public static function allPrefixPhone()
    {
        $countries = self::find()->orderBy(['phonecode' => SORT_ASC])->all();

        $result = [];

        if ($countries) {
            foreach($countries as $country)
            $result['+'.$country->phonecode] = '+'.$country->phonecode;
        }

        return $result;
    }
}