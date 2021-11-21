<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;


class ListingGalleryForm extends Model {

   public $gallery_id;
   public $filename;
   public $files;
   public $image;
   public $listing_id;
   public $is_primary;

   public function rules()
   {
      return [
         [['filename'], 'required', 'on' => ['admin-add']],
         [['gallery_id','files'], 'required', 'on' => ['admin-edit']],
         [['files','listing_id'], 'required', 'on' => ['admin-carkee-add']],
         // ['files', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-add','admin-carkee-edit']],
         // ['files', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-replace-img']],
         ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-add','admin-carkee-edit']],
         ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-replace-img']],
         ['image', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 200, 'on' => ['admin-carkee-gallery']],
         [['gallery_id', 'filename','is_primary','listing_id'], 'safe'],
      ];
   }


}