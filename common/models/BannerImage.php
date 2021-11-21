<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;

class BannerImage extends ActiveRecord
{    
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public static function tableName()
    {
        return '{{%banner_images}}';
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->uploaded_by = Yii::$app->user->getId();
        
        return parent::insert($runValidation, $attributes);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class, ['account_id' => 'account_id']);
    }

    public static function create(\common\forms\BannerImageForm $form, $user_id)
    {
        $banner_img              = new self;
        $banner_img->title       = $form->title;
        $banner_img->filename    = $form->filename;
        $banner_img->content     = $form->content;
        $banner_img->account_id  = $form->account_id;
        $banner_img->status      = !is_null($form->status) ? $form->status : self::STATUS_ACTIVE;
        
        $banner_img->save();

        return $banner_img;
    }

    public function imagelink($hash_id = NULL)
    {

        // if (Common::isApi() OR Common::isCarkeeApi()) {
        //     return ($this->filename)? Url::home(TRUE) . 'file/banner?id=' . $this->id . "?t={$this->filename}" . ($hash_id ? '&account_id=' . $hash_id : NULL)  : '';
        // } else if(Common::isAccount() OR Common::isCpanel()){
        if(Common::isApi()){
            return ($this->filename)? Url::home(TRUE) . 'file/banner?id=' . $this->id . "?t={$this->filename}" . ($hash_id ? '&account_id=' . $hash_id : NULL)  : '';
        }

        return ($this->filename)? Url::home(TRUE) . 'file/banner/' . $this->id . "?t={$this->filename}" . ($hash_id ? '&account_id=' . $hash_id : NULL) : NULL;
    }

    public function fullData($user = NULL)
    {        
        $data = [];
        $data = $this->attributes;
        $data['image'] = $this->imagelink($this->account ? $this->account->hash_id : NULL);
        
        return $data;
    }

    public function data($user = NULL)
    {        
        $data = [
            'id'         => $this->id,
            'image'      => $this->imagelink($this->account ? $this->account->hash_id : NULL)
        ];
        
        return $data;
    }

    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_DELETED        => "Deleted",
            self::STATUS_ACTIVE        => "Active",
            self::STATUS_INACTIVE       => 'Inactive'
        ];
    }

    public function isCarkeeBanner()
    {
        return $this->account_id == 0;
    }
}