<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Item;
use common\helpers\Common;

class ListingForm extends Model
{
    public $file;
    public $listing_id = 0;
    public $title;
    public $content;
    public $filename;
    public $status = 1;
    public $imageFiles;
    public $notification_type;

    public function rules()
    {
        return [
            ['listing_id', 'required', 'on' => ['edit', 'admin-edit']],
            [['title', 'content'], 'required', 'on' => ['add', 'edit', 'admin-add', 'admin-edit']],
            [['status', 'listing_id','notification_type'], 'safe'],
            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['admin-add']],
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['admin-edit','admin-carkee-replace-image']],

            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'on' => ['add']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10 ],
            // ['status', 'validateStatus', 'on' => ['add', 'edit']],
        ];
    }

    public function validateStatus($attr)
    {
        if (!array_key_exists($this->status, Item::statuses())){
            $this->addError($attr, 'Invalid status.');
        }
    }

    public function attributeLabels()
    {
        return [
            'filename'        => 'Listing Image'

        ];
    }

}