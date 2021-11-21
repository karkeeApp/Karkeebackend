<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;

class Media extends ActiveRecord
{    
    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function tableName()
    {
        return '{{%media}}';
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function thumb()
    {
    	return "<img src='" . Url::home() . "file/mediathumb/{$this->media_id}' width=30 />";
    }

    public function url()
    {
    	return Yii::$app->params['frontend.baseUrl'] . "file/media/{$this->media_id}";
    }

    public function extension(){
    	$names = explode('.', $this->filename);

    	return (count($names) < 2) ? '' : $names[count($names)-1];
    }
}