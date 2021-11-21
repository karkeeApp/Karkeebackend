<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class AdsRemoveAttachmentForm extends Model
{
    public $file;
    public $name;
    public $description;
    public $user_id;
    public $id;
    public $ads_id;
    public $account_id = 0;

    public function rules()
    {
        return [
            [['account_id','user_id','id','name'], 'safe'],
            [['description'], 'trim'],
            [['description','ads_id'], 'required'],
            ['file', 'file', 'skipOnEmpty' => FALSE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20],
            
        ];
    }
}
