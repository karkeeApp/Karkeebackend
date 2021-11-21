<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

use common\helpers\Common;

class NewsGallery extends ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	const NON_PRIMARY = 0;
	const IS_PRIMARY = 1;

	public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%news_gallery}}';
    }
    
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::class,
        ];
    }

    public function getNews()
    {
        return $this->hasOne(News::class,['news_id' => 'news_id']);
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

    public static function add(News $news, User $user, $filename, $save = TRUE)
    {
		$gallery             = new self;
		$gallery->account_id = $user->account_id;
		$gallery->news_id    = $news->news_id;
		$gallery->filename   = $filename;
		$gallery->is_primary = self::NON_PRIMARY;
        
        if ($save) $gallery->save();

        return $gallery;
    }
    public static function Create($form, $user, $save = TRUE)
    {
		$gallery             = new self;
		$gallery->account_id = $user->account_id;
		$gallery->news_id    = $form->news_id;
		$gallery->filename   = $form->filename;
		$gallery->is_primary = self::NON_PRIMARY;
        
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
        // if (Common::isApi() OR Common::isCarkeeApi()) {
        //     return ($this->filename)? Url::home(TRUE) . 'file/news-gallery?id=' . $this->gallery_id : ''; // . '&access-token=' . Yii::$app->request->get('access-token') : '';
        // } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->filename)? Url::home(TRUE) . 'file/news-gallery?id=' . $this->gallery_id : '';
        // }

        return ($this->filename)? Url::home(TRUE) . 'file/news-gallery/' . $this->gallery_id : NULL ; // . '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }

    public function isCarkeeGallery()
    {
        return $this->account_id == 0;
    }
}