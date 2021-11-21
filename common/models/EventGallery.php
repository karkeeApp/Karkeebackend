<?php
namespace common\models;

use common\helpers\Common;
use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class EventGallery extends ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%event_gallery}}';
    }
    
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::class,
        ];
    }

    public function getEvent()
    {
        return $this->hasOne(Event::class,['event_id' => 'event_id']);
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        return parent::update($runValidation, $attributes);
    }

    public function isDeleted()
    {
    	return $this->status == self::STATUS_DELETED;
    }

    public static function add(Event $event, User $user, $filename, $save = TRUE)
    {
        $gallery             = new self;
        $gallery->account_id = $user->account_id;
        $gallery->event_id   = $event->event_id;
        $gallery->filename   = $filename;
        
        if ($save) $gallery->save();

        return $gallery;
    }

    public static function Create($form, $user, $save = TRUE)
    {
        $gallery             = new self;
        $gallery->account_id = $user->account_id;
        $gallery->event_id   = $form->event_id;
        $gallery->filename   = $form->filename;
        
        if ($save) $gallery->save();

        return $gallery;
    }

    public function data(){
        $data = $this->attributes;

        $data['link'] = $this->filelink();

        return $data;
    }
    
    public function filelink()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->filename)? Url::home(TRUE) . 'file/event-gallery?id=' . $this->gallery_id . '&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->filename)? Url::home(TRUE) . 'file/event-gallery?id=' . $this->gallery_id : '';
        }

        return ($this->filename)? Url::home(TRUE) . 'file/event-gallery/' . $this->gallery_id . '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }


    public function isCarkeeGallery()
    {
        return $this->account_id == 0;
    }
}