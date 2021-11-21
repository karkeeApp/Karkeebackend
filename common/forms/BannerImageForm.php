<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\BannerImage;

/**
 * Banner Image Form
 */
class BannerImageForm extends Model
{
    public $id;
    public $banner_id;
    public $account_id;
    public $title;
    public $image;
    public $filename;
    public $content;
    public $status;

    public function rules()
    {
        return [
            [['title'], 'required','on' => ['account_add', 'account_edit', 'admin_add', 'admin_edit','admin-carkee-edit']],
            [['id'], 'required','on' => ['account_edit', 'admin_edit','admin-carkee-edit','admin-replace-image','admin-carkee-replace-image']],
            [['account_id'], 'required','on' => ['account_add', 'admin_add','admin-carkee-add']],
            ['image', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['account_add', 'admin_add','admin-carkee-add']],
            ['image', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['account_edit', 'admin_edit','admin-carkee-edit','admin-replace-image','admin-carkee-replace-image']],
            // ['title','default', 'value' => 'Guest/Main Screen Banner Images'],
            ['status', 'default', 'value' => BannerImage::STATUS_ACTIVE],
            ['status', 'in', 'range' => [BannerImage::STATUS_DELETED, BannerImage::STATUS_ACTIVE, BannerImage::STATUS_INACTIVE]],
            ['content', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image'        => 'Banner Image'
        ];
    }
}
