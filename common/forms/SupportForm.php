<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class SupportForm extends Model
{
    public $id;
    public $description;
    public $message;
    public $title;
    public $user_id = 0;
    public $account_id = 0;

    public function rules()
    {
        return [

            [['account_id','user_id','description','title','message'], 'safe'],
            [['description','title','message'], 'trim'],
            [['title'], 'string','max'=>255],
            [['account_id','user_id','id'], 'integer'],
            [['message'], 'required', 'on' => ['add-support','inquire']],
            [['id', 'description'], 'required', 'on' => ['edit-support']],
        ];
    }
}
