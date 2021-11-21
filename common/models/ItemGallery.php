<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class ItemGallery extends ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%item_gallery}}';
    }
    
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::class,
        ];
    }

    public function getItem()
    {
        return $this->hasOne(Item::class,['item_id' => 'item_id']);
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

    public static function add(Item $item, User $user, $filename)
    {
		$gallery             = new self;
		$gallery->user_id    = $user->user_id;
		$gallery->account_id = $user->account_id;
		$gallery->item_id    = $item->item_id;
		$gallery->filename   = $filename;
        $gallery->save();

        return $gallery;
    }

    public function filelink()
    {
        return ($this->filename)? Url::home(TRUE) . 'item/gallery?id=' . $this->gallery_id . '&access-token=' . Yii::$app->request->get('access-token') : NULL;
    }
    public function isCarkeeGallery()
    {
        return $this->account_id == 0;
    }
}