<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

/**
 * Login form
 */
class NewsGalleryForm extends Model
{
    public $gallery_id;
    public $filename;
    public $files;
    public $is_primary;
    public $news_id;

    public $image;

    public function rules()
    {
        return [
            [['filename'], 'required', 'on' => ['account_add', 'admin_add']],
            [['files','news_id'], 'required', 'on' => ['admin-carkee-add']],
            [['gallery_id','files'], 'required', 'on' => ['admin-carkee-edit','admin-carkee-replace-img']],
            ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-add','admin-carkee-edit']],
            ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-replace-img']],
            ['image', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-gallery']],
            [['news_id'], 'required', 'on' => ['admin-carkee-gallery']],
            [['gallery_id', 'files', 'filename','is_primary','news_id', 'image'], 'safe'],
        ];
    }
}
