<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class AdsRemoveAttachmentForm extends Model
{
    public $file;
    public $filename;
    public $log_card;
    public $log_card_file;
    public $name;
    public $description;
    public $user_id;
    public $id;
    public $ads_id;
    public $account_id = 0;

    public function rules()
    {
        return [
            [['account_id','user_id','id','name','log_card_file','file','filename','log_card'], 'safe'],
            [['description'], 'trim'],
            [['description','ads_id'], 'required', 'on' => ['remove-ads']],
            [['file'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['remove-ads']]
        ];
    }
}
