<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

/**
 * Login form
 */
class MediaForm extends Model
{
    public $media_id;
    public $filename;
    public $title;

    public function rules()
    {
        return [
            [['title'], 'trim'],
            [['filename', 'title'], 'required', 'on' => ['account_add', 'admin_add','admin-carkee-add']],
            [['media_id', 'filename', 'title'], 'safe'],
        ];
    }
}
