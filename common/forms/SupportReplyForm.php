<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class SupportReplyForm extends Model
{
    public $id;
    public $support_id;
    public $message;
    public $title;
    public $user_id = 0;
    public $account_id = 0;

    public function rules()
    {
        return [

            [['account_id','user_id','support_id','title','message'], 'safe'],
            [['title','message'], 'trim'],
            [['title'], 'string','max'=>255],
            [['account_id','user_id','id'], 'integer'],
            [['message'], 'required', 'on' => ['add-support-reply']],
            [['id', 'message'], 'required', 'on' => ['edit-support-reply']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'message'        => 'Reply'

        ];
    }
}
