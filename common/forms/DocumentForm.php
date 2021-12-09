<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class DocumentForm extends Model
{
    public $filename;
    public $doc_id;
    public $user_id;
    public $status;
    public $type;
    public $account_id = 0;

    public function rules()
    {
        return [
            [['account_id','user_id','doc_id','status','type','filename'], 'safe']           
        ];
    }
}
