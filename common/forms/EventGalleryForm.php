<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

/**
 * Login form
 */
class EventGalleryForm extends Model
{
    public $gallery_id;
    public $filename;
    public $files;
    public $image;
    public $event_id;
    public $is_primary;

    public function rules()
    {
        return [
            [['filename'], 'required', 'on' => ['account_add', 'admin_add']],
            [['gallery_id','files'], 'required', 'on' => ['admin-carkee-edit','admin-carkee-replace-img']],
            [['files','event_id'], 'required', 'on' => ['admin-carkee-add']],
            // ['files', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-add','admin-carkee-edit']],
            // ['files', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-replace-img']],
            ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-add','admin-carkee-edit']],
            ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-replace-img']],
            ['image', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-gallery']],
            [['gallery_id', 'filename','is_primary','event_id'], 'safe'],
        ];
    }
}
