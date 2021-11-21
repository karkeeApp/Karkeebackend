<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class ListingGallery extends ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%listing_gallery}}';
    }
    
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::class,
        ];
    }

    public function getListing()
    {
        return $this->hasOne(Item::class,['listing_id' => 'listing_id']);
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

    public static function add(Listing $listing, User $user, $filename)
    {
        $gallery             = new self;
        $gallery->user_id    = $user->user_id;
        $gallery->account_id = $user->account_id;
        $gallery->listing_id = $listing->listing_id;
        $gallery->filename   = $filename;
        $gallery->is_primary = 0;
        $gallery->save();

        return $gallery;
    }

    public function filelink($hash_id = NULL)
    {
        return ($this->filename)? Url::home(TRUE) . 'listing/gallery/?id=' . $this->gallery_id . "&t=" . base64_encode($this->filename) . ($hash_id ? '&account_id=' . $hash_id : NULL) : NULL;
    }

    public function isCarkeeGallery()
    {
        return $this->account_id == 0;
    }

    public function data(){
        $data = $this->attributes;

        $data['link'] = $this->filelink();

        return $data;
    }
}