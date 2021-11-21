<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class AccountSecurityQuestionsForm extends Model
{
    public $account_id = 0;
    public $question;
    public $is_file_upload;

    public function rules()
    {
        return [
            [['account_id', 'is_file_upload'],'integer'],
            ['question','string'],
            [['question','account_id'],'required']
        ];
    }

}