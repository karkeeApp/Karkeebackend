<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

class ItemReplaceUploadForm extends Model
{
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }    
}