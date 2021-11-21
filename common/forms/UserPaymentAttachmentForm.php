<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class UserPaymentAttachmentForm extends Model
{
    public $file;
    public $filename;
    public $name;
    public $description;
    public $amount;
    public $user_id;
    public $id;
    public $payment_id;
    public $account_id = 0;

    public function rules()
    {
        return [
            [['account_id','user_id','id','name','amount','filename'], 'safe'],
            [['description'], 'trim'],
            [['amount'], 'double'],
            [['description','payment_id'], 'required'],
            ['file', 'file', 'skipOnEmpty' => FALSE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20],
            
        ];
    }
}
