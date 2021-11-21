<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

class ListingUploadForm extends Model
{
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
        ];
    }    
}