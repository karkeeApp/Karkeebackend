<?php
namespace common\forms;

use Yii;
use yii\base\Model;

/**
 * Banner Management form
 */
class BannerManagementForm extends Model
{
    public $account_id;
    public $image;
    public $title;
    public function rules()
    {
        return [
            [['image','title','account_id'], 'safe'],
            [['account_id'], 'integer','on' => ['create','admin-carkee-add']],
            [['image','title'], 'required','on' => ['create','admin-carkee-add']],
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'on' => ['create','admin-carkee-add']],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'on' => ['update','admin-carkee-edit']],
            // ['title','default', 'value' => 'Guest/Main Screen Banner Images'],
            // ['image', 'validateImages', 'on' => ['update']]            
        ];
    }
    // public function validateImages($attr)
    // {
    //     if (empty($this->bannerImages)) {
    //         return $this->addError('file', 'Upload at least one image');   
    //     }
    // }
}
