<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;

class BannerManagement extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public static function tableName()
    {
        return '{{%banner_management}}';
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->managed_by = Yii::$app->user->getId();
        
        return parent::insert($runValidation, $attributes);
    } 

    public static function create(\common\forms\BannerManagementForm $form, $user_id)
    {
        $banner              = new self;
        $banner->title       = $form->title;
        
        $banner->save();

        return $banner;
    }

    public function getBannerImages()
    {
        return $this->hasMany(BannerImage::classname(), ['banner_id' => 'id']);
    }

    public function data()
    {        
        $data = [
            'id'         => $this->id,
            'title'      => $this->title,
            'created_at' => $this->created_at
        ];
        
        if ($this->bannerImages) {
            foreach ($this->bannerImages as $key => $bannerImages) {
                $data['images'][] = $bannerImages->data();
            }
        }

        return $data;
    }
    
    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE        => "Active",
            self::STATUS_DELETED       => 'Deleted'
        ];
    }
}